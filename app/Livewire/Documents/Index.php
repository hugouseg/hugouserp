<?php

declare(strict_types=1);

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentTag;
use App\Services\DocumentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $folder = '';

    #[Url]
    public ?int $tag = null;

    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected DocumentService $documentService;

    public function boot(DocumentService $documentService): void
    {
        $this->documentService = $documentService;
    }

    public function mount(): void
    {
        $this->authorize('documents.view');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete(int $id): void
    {
        $this->authorize('documents.delete');

        $document = Document::findOrFail($id);
        $this->documentService->deleteDocument($document);

        session()->flash('success', __('Document deleted successfully'));
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();
        $branchId = $user->branch_id;

        // Build query
        $query = Document::with(['uploader', 'tags'])
            ->where(function ($q) use ($user) {
                $q->where('uploaded_by', $user->id)
                    ->orWhere('is_public', true)
                    ->orWhereHas('shares', function ($shareQuery) use ($user) {
                        $shareQuery->where('user_id', $user->id)->active();
                    });
            })
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($this->search, fn($q) => $q->where(function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhere('file_name', 'like', "%{$this->search}%");
            }))
            ->when($this->category, fn($q) => $q->where('category', $this->category))
            ->when($this->folder, fn($q) => $q->where('folder', $this->folder))
            ->when($this->tag, fn($q) => $q->whereHas('tags', fn($tq) => $tq->where('document_tags.id', $this->tag)));

        $documents = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // Get statistics
        $stats = $this->documentService->getStatistics($branchId);

        // Get filter options
        $tags = DocumentTag::all();
        $categories = Document::select('category')
            ->whereNotNull('category')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->distinct()
            ->pluck('category');
        $folders = Document::select('folder')
            ->whereNotNull('folder')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->distinct()
            ->pluck('folder');

        return view('livewire.documents.index', [
            'documents' => $documents,
            'stats' => $stats,
            'tags' => $tags,
            'categories' => $categories,
            'folders' => $folders,
        ]);
    }
}
