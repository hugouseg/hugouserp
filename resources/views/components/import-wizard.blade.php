{{-- resources/views/components/import-wizard.blade.php --}}
@props([
    'title' => 'Import Data',
    'entity' => 'items',
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-xl shadow-sm']) }}>
    <div class="border-b border-slate-200 dark:border-slate-700 px-6 py-4">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
            {{ $title }}
        </h3>
    </div>

    <div class="p-6">
        @if(!isset($currentStep) || $currentStep === 1)
        {{-- Step 1: Upload & Mapping --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center font-semibold">1</div>
                    <span class="font-medium text-slate-900 dark:text-slate-100">{{ __('Upload & Map Columns') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-full flex items-center justify-center">2</div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Preview') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-full flex items-center justify-center">3</div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Import') }}</span>
                </div>
            </div>

            <div class="border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-8 text-center">
                <input type="file" wire:model="importFile" accept=".csv,.xlsx,.xls" class="hidden" id="import-file-input">
                <label for="import-file-input" class="cursor-pointer">
                    <svg class="mx-auto h-12 w-12 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                        {{ __('Click to upload or drag and drop') }}
                    </p>
                    <p class="text-xs text-slate-500">
                        {{ __('CSV, XLSX or XLS (MAX. 10MB)') }}
                    </p>
                </label>
            </div>

            <div class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model="hasHeaders" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    <span>{{ __('First row contains headers') }}</span>
                </label>
            </div>
        </div>

        @elseif($currentStep === 2)
        {{-- Step 2: Preview --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center">✓</div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Upload & Map') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center font-semibold">2</div>
                    <span class="font-medium text-slate-900 dark:text-slate-100">{{ __('Preview Data') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 rounded-full flex items-center justify-center">3</div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Import') }}</span>
                </div>
            </div>

            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-4">
                <p class="text-sm text-amber-800 dark:text-amber-200">
                    {{ __('Showing first 10 rows. Please verify the data before proceeding.') }}
                </p>
            </div>

            <div class="overflow-x-auto border border-slate-200 dark:border-slate-700 rounded-lg">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-slate-500 uppercase">{{ __('Row') }}</th>
                            {{-- Dynamic columns will go here --}}
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        {{-- Preview data rows will go here --}}
                    </tbody>
                </table>
            </div>
        </div>

        @elseif($currentStep === 3)
        {{-- Step 3: Confirm & Import --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center">✓</div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Upload & Map') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center">✓</div>
                    <span class="text-sm text-slate-500 dark:text-slate-400">{{ __('Preview') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center font-semibold">3</div>
                    <span class="font-medium text-slate-900 dark:text-slate-100">{{ __('Confirm & Import') }}</span>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-6 text-center">
                <svg class="mx-auto h-16 w-16 text-emerald-600 dark:text-emerald-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-2">
                    {{ __('Ready to Import') }}
                </h4>
                <p class="text-sm text-slate-600 dark:text-slate-400">
                    {{ __('Click the button below to start importing your data.') }}
                </p>
            </div>

            <div class="flex items-center gap-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <input type="checkbox" wire:model="dryRun" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <div class="flex-1">
                    <label class="text-sm font-medium text-slate-900 dark:text-slate-100 cursor-pointer">
                        {{ __('Dry run only (do not save to database)') }}
                    </label>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                        {{ __('Test the import without making any changes') }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Footer Actions --}}
    <div class="border-t border-slate-200 dark:border-slate-700 px-6 py-4 flex justify-between">
        @if(isset($currentStep) && $currentStep > 1)
        <x-ui.button wire:click="previousStep" variant="secondary">
            {{ __('Back') }}
        </x-ui.button>
        @else
        <div></div>
        @endif

        @if(!isset($currentStep) || $currentStep < 3)
        <x-ui.button wire:click="nextStep" variant="primary">
            {{ __('Next') }}
        </x-ui.button>
        @else
        <x-ui.button wire:click="runImport" variant="primary" :loading="$importing ?? false">
            {{ __('Start Import') }}
        </x-ui.button>
        @endif
    </div>
</div>
