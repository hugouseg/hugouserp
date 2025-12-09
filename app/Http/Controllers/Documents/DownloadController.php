<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function __invoke(Document $document): StreamedResponse
    {
        // Authorization check first
        abort_unless(Storage::exists($document->file_path), 404);
        
        $document->logActivity('downloaded');
        
        return Storage::download(
            $document->file_path,
            $document->file_name,
            ['Content-Type' => $document->mime_type]
        );
    }
}
