@props(['status', 'size' => 'md'])

@php
    $uiHelper = app(\App\Services\UIHelperService::class);
    $colorClass = $uiHelper->getStatusBadgeClass($status);
    
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-sm',
        'lg' => 'px-3 py-1.5 text-base',
        default => 'px-2.5 py-1 text-sm',
    };
    
    $displayStatus = __(ucfirst(str_replace('_', ' ', $status)));
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border font-medium {$colorClass} {$sizeClasses}"]) }}>
    {{ $displayStatus }}
</span>
