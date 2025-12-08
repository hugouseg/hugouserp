<?php

declare(strict_types=1);

namespace App\Livewire\Documents\Tags;

use App\Models\DocumentTag;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $slug = '';
    public string $color = '#3B82F6';
    public string $description = '';

    public function mount(): void
    {
        $this->authorize('documents.tags.manage');
    }

    public function openModal(?int $id = null): void
    {
        $this->resetForm();

        if ($id) {
            $tag = DocumentTag::findOrFail($id);
            $this->editingId = $id;
            $this->fill($tag->only(['name', 'slug', 'color', 'description']));
        }

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:100|unique:document_tags,name,' . ($this->editingId ?? 'NULL'),
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name' => $this->name,
            'color' => $this->color,
            'description' => $this->description,
        ];

        if ($this->editingId) {
            $tag = DocumentTag::findOrFail($this->editingId);
            $tag->update($data);
            session()->flash('success', __('Tag updated successfully'));
        } else {
            DocumentTag::create($data);
            session()->flash('success', __('Tag created successfully'));
        }

        $this->closeModal();
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $tag = DocumentTag::findOrFail($id);
        
        if ($tag->getDocumentCount() > 0) {
            session()->flash('error', __('Cannot delete tag that is being used'));
            return;
        }

        $tag->delete();
        session()->flash('success', __('Tag deleted successfully'));
        $this->resetPage();
    }

    protected function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->color = '#3B82F6';
        $this->description = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $tags = DocumentTag::withCount('documents')
            ->orderBy('name')
            ->paginate(20);

        return view('livewire.documents.tags.index', [
            'tags' => $tags,
        ]);
    }
}
