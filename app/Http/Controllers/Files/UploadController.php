<?php

declare(strict_types=1);

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Store an uploaded file and return its public path/url.
     *
     * Security features:
     * - File type validation against whitelist
     * - File size limits enforced
     * - Random filename generation to prevent overwriting
     * - MIME type verification
     * - Extension validation
     *
     * Accepts: file, disk?=public, dir?=uploads (auto y/m), visibility?=public|private
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define allowed MIME types and extensions for security
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'application/pdf',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/plain', 'text/csv',
        ];

        $allowedExtensions = [
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            'pdf',
            'doc', 'docx',
            'xls', 'xlsx',
            'txt', 'csv',
        ];

        $this->validate($request, [
            'file' => [
                'required',
                'file',
                'max:10240', // 10MB maximum size
                function ($attribute, $value, $fail) use ($allowedMimes, $allowedExtensions) {
                    // Validate MIME type
                    if (! in_array($value->getMimeType(), $allowedMimes, true)) {
                        $fail(__('The file type is not allowed. Allowed types: images, PDF, Word, Excel, text.'));
                    }
                    // Validate extension
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (! in_array($ext, $allowedExtensions, true)) {
                        $fail(__('The file extension is not allowed.'));
                    }
                },
            ],
            'disk' => ['sometimes', 'string', 'in:public,local,private'], // Limit allowed disks
            'dir' => ['sometimes', 'string', 'max:100'], // Prevent path traversal with length limit
            'visibility' => ['sometimes', 'in:public,private'],
        ]);

        $disk = (string) $request->input('disk', config('filesystems.default', 'public'));

        // Sanitize directory path to prevent path traversal attacks
        $baseDir = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', trim((string) $request->input('dir', 'uploads'), '/'));
        $baseDir = str_replace(['..', '~'], '', $baseDir); // Remove potential path traversal
        $dir = $baseDir.'/'.now()->format('Y/m');

        $uploaded = $request->file('file');

        // Use server-detected extension instead of client-provided for security
        $ext = strtolower($uploaded->guessExtension() ?? $uploaded->getClientOriginalExtension());

        // Validate extension one more time
        if (! in_array($ext, $allowedExtensions, true)) {
            return $this->fail(__('Invalid file type detected.'), 422);
        }

        // Generate secure random filename
        $name = Str::random(32).($ext ? ('.'.$ext) : '');

        // Store file with secure settings
        $path = $uploaded->storeAs($dir, $name, [
            'disk' => $disk,
            'visibility' => $request->input('visibility', 'public'),
        ]);

        $url = null;
        try {
            $url = Storage::disk($disk)->url($path);
        } catch (\Throwable) {
            // Some disks (local private) may not support url()
            // This is expected for private files
        }

        // Log file upload for audit trail
        Log::info('File uploaded', [
            'user_id' => $request->user()?->id,
            'path' => $path,
            'original_name' => $uploaded->getClientOriginalName(),
            'mime' => $uploaded->getMimeType(),
            'size' => $uploaded->getSize(),
        ]);

        return $this->ok([
            'disk' => $disk,
            'path' => $path,
            'url' => $url,
            'mime' => $uploaded->getMimeType(), // Use server-detected MIME type
            'size' => $uploaded->getSize(),
            'original_name' => $uploaded->getClientOriginalName(),
        ], __('File uploaded successfully'));
    }
}
