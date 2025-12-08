<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    /**
     * Upload a new document
     */
    public function uploadDocument(UploadedFile $file, array $data): Document
    {
        return DB::transaction(function () use ($file, $data) {
            // Store the file
            $path = $file->store('documents', 'public');

            // Create document record
            $document = Document::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'folder' => $data['folder'] ?? null,
                'category' => $data['category'] ?? null,
                'is_public' => $data['is_public'] ?? false,
                'uploaded_by' => auth()->id(),
                'branch_id' => $data['branch_id'] ?? auth()->user()->branch_id,
                'metadata' => $data['metadata'] ?? null,
            ]);

            // Create initial version
            DocumentVersion::create([
                'document_id' => $document->id,
                'version_number' => 1,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => auth()->id(),
                'change_notes' => 'Initial upload',
            ]);

            // Log activity
            $document->logActivity('created', auth()->user());

            // Attach tags if provided
            if (!empty($data['tags'])) {
                $document->tags()->sync($data['tags']);
            }

            return $document;
        });
    }

    /**
     * Upload a new version of existing document
     */
    public function uploadVersion(Document $document, UploadedFile $file, ?string $changeNotes = null): DocumentVersion
    {
        return DB::transaction(function () use ($document, $file, $changeNotes) {
            // Store the file
            $path = $file->store('documents', 'public');

            // Get next version number
            $nextVersion = $document->versions()->max('version_number') + 1;

            // Create version record
            $version = DocumentVersion::create([
                'document_id' => $document->id,
                'version_number' => $nextVersion,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => auth()->id(),
                'change_notes' => $changeNotes,
            ]);

            // Update document with new version info
            $document->update([
                'version' => $nextVersion,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);

            // Log activity
            $document->logActivity('version_created', auth()->user(), [
                'version' => $nextVersion,
                'change_notes' => $changeNotes,
            ]);

            return $version;
        });
    }

    /**
     * Update document metadata
     */
    public function updateDocument(Document $document, array $data): Document
    {
        return DB::transaction(function () use ($document, $data) {
            $document->update([
                'title' => $data['title'] ?? $document->title,
                'description' => $data['description'] ?? $document->description,
                'folder' => $data['folder'] ?? $document->folder,
                'category' => $data['category'] ?? $document->category,
                'is_public' => $data['is_public'] ?? $document->is_public,
                'metadata' => $data['metadata'] ?? $document->metadata,
            ]);

            // Update tags if provided
            if (isset($data['tags'])) {
                $document->tags()->sync($data['tags']);
            }

            // Log activity
            $document->logActivity('updated', auth()->user());

            return $document->fresh();
        });
    }

    /**
     * Share document with user
     */
    public function shareDocument(Document $document, int $userId, string $permission = 'view', ?\DateTime $expiresAt = null): void
    {
        DB::transaction(function () use ($document, $userId, $permission, $expiresAt) {
            $document->shares()->updateOrCreate(
                ['user_id' => $userId],
                [
                    'shared_by' => auth()->id(),
                    'permission' => $permission,
                    'expires_at' => $expiresAt,
                ]
            );

            // Log activity
            $document->logActivity('shared', auth()->user(), [
                'shared_with_user_id' => $userId,
                'permission' => $permission,
            ]);
        });
    }

    /**
     * Unshare document from user
     */
    public function unshareDocument(Document $document, int $userId): void
    {
        DB::transaction(function () use ($document, $userId) {
            $document->shares()->where('user_id', $userId)->delete();

            // Log activity
            $document->logActivity('unshared', auth()->user(), [
                'unshared_from_user_id' => $userId,
            ]);
        });
    }

    /**
     * Delete document
     */
    public function deleteDocument(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            // Delete all file versions from storage
            Storage::disk('public')->delete($document->file_path);
            
            foreach ($document->versions as $version) {
                Storage::disk('public')->delete($version->file_path);
            }

            // Log activity before deletion
            $document->logActivity('deleted', auth()->user());

            // Soft delete the document (cascades to versions, shares, activities)
            return $document->delete();
        });
    }

    /**
     * Download document and log activity
     */
    public function downloadDocument(Document $document, User $user): string
    {
        // Check access
        if (!$document->canBeAccessedBy($user)) {
            abort(403, 'You do not have permission to download this document');
        }

        // Log activity
        $document->logActivity('downloaded', $user, [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Increment access count if shared
        $share = $document->shares()->where('user_id', $user->id)->first();
        if ($share) {
            $share->incrementAccessCount();
        }

        return Storage::disk('public')->path($document->file_path);
    }

    /**
     * Get document statistics
     */
    public function getStatistics(?int $branchId = null): array
    {
        $query = Document::query();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        $totalSize = $query->sum('file_size');

        return [
            'total_documents' => $query->count(),
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
            'by_category' => $query->select('category', DB::raw('count(*) as count'))
                ->whereNotNull('category')
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
            'recent_uploads' => $query->latest()->limit(5)->get(),
            'most_downloaded' => Document::withCount('activities')
                ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
                ->orderBy('activities_count', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
