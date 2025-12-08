<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">{{ __('Document Tags') }}</h1>
            <p class="text-sm text-slate-500">{{ __('Manage document tags and labels') }}</p>
        </div>
        <button wire:click="openModal" class="erp-btn erp-btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('New Tag') }}
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-start text-xs font-medium text-slate-500 uppercase">{{ __('Name') }}</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-slate-500 uppercase">{{ __('Slug') }}</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-slate-500 uppercase">{{ __('Documents') }}</th>
                    <th class="px-6 py-3 text-start text-xs font-medium text-slate-500 uppercase">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($tags as $tag)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($tag->color)
                                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $tag->color }}"></span>
                                @endif
                                <span class="font-medium">{{ $tag->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $tag->slug }}</td>
                        <td class="px-6 py-4 text-sm">{{ $tag->documents_count }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <button wire:click="openModal({{ $tag->id }})" class="text-blue-600 hover:text-blue-900">{{ __('Edit') }}</button>
                                <button wire:click="delete({{ $tag->id }})" wire:confirm="{{ __('Are you sure?') }}" class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">{{ __('No tags found') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">{{ $tags->links() }}</div>
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 bg-slate-900/50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-lg w-full">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">{{ $editingId ? __('Edit Tag') : __('New Tag') }}</h2>
                    <form wire:submit="save" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="erp-input w-full" required>
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('Color') }}</label>
                            <input type="color" wire:model="color" class="erp-input w-full h-10">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('Description') }}</label>
                            <textarea wire:model="description" rows="3" class="erp-input w-full"></textarea>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-4 border-t">
                            <button type="button" wire:click="closeModal" class="erp-btn erp-btn-secondary">{{ __('Cancel') }}</button>
                            <button type="submit" class="erp-btn erp-btn-primary">{{ $editingId ? __('Update') : __('Create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
