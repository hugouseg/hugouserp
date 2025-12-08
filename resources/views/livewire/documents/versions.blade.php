<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ __('Version History') }} - {{ $document->title }}</h1>
        <p class="text-sm text-slate-500">{{ __('Manage document versions') }}</p>
    </div>

    {{-- Upload New Version --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">{{ __('Upload New Version') }}</h2>
        <form wire:submit="uploadVersion" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('File') }} <span class="text-red-500">*</span></label>
                <input type="file" wire:model="file" class="erp-input w-full" required>
                <p class="text-xs text-slate-500 mt-1">{{ __('Maximum file size: 50MB') }}</p>
                @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">{{ __('Change Notes') }}</label>
                <textarea wire:model="changeNotes" rows="3" class="erp-input w-full" placeholder="{{ __('Describe what changed in this version...') }}"></textarea>
                @error('changeNotes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="erp-btn erp-btn-primary">{{ __('Upload Version') }}</button>
        </form>
    </div>

    {{-- Version List --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">{{ __('All Versions') }}</h2>
        <div class="space-y-4">
            @foreach($document->versions->sortByDesc('version_number') as $version)
                <div class="flex items-start gap-4 p-4 border rounded-lg {{ $version->version_number === $document->version ? 'bg-blue-50 border-blue-300' : 'bg-slate-50' }}">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">
                            v{{ $version->version_number }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="font-semibold text-slate-900">{{ __('Version') }} {{ $version->version_number }}</p>
                                @if($version->version_number === $document->version)
                                    <span class="text-xs bg-blue-500 text-white px-2 py-0.5 rounded">{{ __('Current') }}</span>
                                @endif
                            </div>
                            <span class="text-sm text-slate-500">{{ $version->getFileSizeFormatted() }}</span>
                        </div>
                        <p class="text-sm text-slate-600 mb-2">{{ $version->file_name }}</p>
                        @if($version->change_notes)
                            <p class="text-sm text-slate-700 bg-white p-2 rounded border">{{ $version->change_notes }}</p>
                        @endif
                        <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                            <span>{{ __('By') }} {{ $version->uploader->name }}</span>
                            <span>{{ $version->created_at->format('Y-m-d H:i') }}</span>
                            <span>{{ $version->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex items-center justify-start">
        <a href="{{ route('documents.show', $document->id) }}" class="erp-btn erp-btn-secondary">{{ __('Back to Document') }}</a>
    </div>
</div>
