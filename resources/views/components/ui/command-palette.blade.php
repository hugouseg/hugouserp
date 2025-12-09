{{-- resources/views/components/ui/command-palette.blade.php --}}
<div x-data="{ 
    open: false,
    init() {
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                this.open = !this.open;
            }
            if (e.key === 'Escape') {
                this.open = false;
            }
        });
    }
}" x-cloak>
    {{-- Overlay --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-slate-900/50 dark:bg-slate-950/70 backdrop-blur-sm"
         @click="open = false">
    </div>

    {{-- Command Palette Modal --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20 pointer-events-none">
        <div class="mx-auto max-w-2xl pointer-events-auto" @click.stop>
            @livewire('command-palette')
        </div>
    </div>
</div>

{{-- Hint Badge (Ctrl+K) --}}
<div class="hidden lg:flex items-center gap-1 px-2 py-1 text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded border border-slate-200 dark:border-slate-700 cursor-pointer hover:bg-slate-200 dark:hover:bg-slate-700 transition"
     @click="document.dispatchEvent(new KeyboardEvent('keydown', { key: 'k', ctrlKey: true }))">
    <span>{{ __('Search') }}</span>
    <kbd class="px-1.5 py-0.5 text-xs font-semibold bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded">
        Ctrl+K
    </kbd>
</div>
