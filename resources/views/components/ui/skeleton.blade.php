{{-- resources/views/components/ui/skeleton.blade.php --}}
@props([
    'type' => 'default', // default, table, card, text, avatar, button
    'rows' => 3,
    'animate' => true,
])

@php
$animateClass = $animate ? 'animate-pulse' : '';
@endphp

@if($type === 'table')
<div {{ $attributes->merge(['class' => 'w-full']) }}>
    {{-- Table Header Skeleton --}}
    <div class="flex gap-4 mb-4 pb-4 border-b border-slate-200 dark:border-slate-700">
        @for($i = 0; $i < 5; $i++)
        <div class="flex-1 h-4 bg-slate-200 dark:bg-slate-700 rounded {{ $animateClass }}"></div>
        @endfor
    </div>
    
    {{-- Table Rows Skeleton --}}
    @for($r = 0; $r < $rows; $r++)
    <div class="flex gap-4 mb-3">
        @for($i = 0; $i < 5; $i++)
        <div class="flex-1 h-4 bg-slate-100 dark:bg-slate-800 rounded {{ $animateClass }}"></div>
        @endfor
    </div>
    @endfor
</div>

@elseif($type === 'card')
<div {{ $attributes->merge(['class' => 'p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm']) }}>
    <div class="h-6 bg-slate-200 dark:bg-slate-700 rounded w-1/3 mb-4 {{ $animateClass }}"></div>
    <div class="space-y-3">
        @for($i = 0; $i < $rows; $i++)
        <div class="h-4 bg-slate-100 dark:bg-slate-800 rounded {{ $animateClass }}"></div>
        @endfor
    </div>
    <div class="mt-4 flex gap-2">
        <div class="h-10 w-24 bg-slate-200 dark:bg-slate-700 rounded-lg {{ $animateClass }}"></div>
        <div class="h-10 w-24 bg-slate-200 dark:bg-slate-700 rounded-lg {{ $animateClass }}"></div>
    </div>
</div>

@elseif($type === 'text')
<div {{ $attributes->merge(['class' => 'space-y-3']) }}>
    @for($i = 0; $i < $rows; $i++)
    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded {{ $animateClass }}" style="width: {{ rand(60, 100) }}%;"></div>
    @endfor
</div>

@elseif($type === 'avatar')
<div {{ $attributes->merge(['class' => 'flex items-center gap-4']) }}>
    <div class="w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded-full {{ $animateClass }}"></div>
    <div class="flex-1 space-y-2">
        <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded w-1/4 {{ $animateClass }}"></div>
        <div class="h-3 bg-slate-100 dark:bg-slate-800 rounded w-1/3 {{ $animateClass }}"></div>
    </div>
</div>

@elseif($type === 'button')
<div {{ $attributes->merge(['class' => 'h-10 w-32 bg-slate-200 dark:bg-slate-700 rounded-lg']) }} class="{{ $animateClass }}"></div>

@else
{{-- Default skeleton --}}
<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @for($i = 0; $i < $rows; $i++)
    <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded {{ $animateClass }}"></div>
    @endfor
</div>
@endif
