{{-- resources/views/components/dashboard/recent-activity.blade.php --}}
@props([
    'limit' => 10,
])

@php
// For now, we'll use a simple activity log structure
// This can be enhanced with actual ActivityLog model once created
$activities = collect([]);

// Check if we have an ActivityLog model, otherwise show placeholder
if (class_exists('\App\Models\ActivityLog')) {
    $activities = \App\Models\ActivityLog::with('user')
        ->latest()
        ->limit($limit)
        ->get();
}
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6']) }}>
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
            {{ __('Recent Activity') }}
        </h3>
        <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 dark:text-emerald-500 dark:hover:text-emerald-400">
            {{ __('View All') }}
        </a>
    </div>

    @if($activities->isEmpty())
    <div class="text-center py-8">
        <div class="text-4xl mb-2">📋</div>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            {{ __('No recent activity') }}
        </p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($activities as $activity)
        <div class="flex items-start gap-3 pb-3 border-b border-slate-100 dark:border-slate-700 last:border-0 last:pb-0">
            <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center">
                <span class="text-sm">{{ substr($activity->user?->name ?? 'U', 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-slate-900 dark:text-slate-100">
                    <span class="font-medium">{{ $activity->user?->name ?? __('User') }}</span>
                    <span class="text-slate-600 dark:text-slate-400">{{ $activity->description }}</span>
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    {{ $activity->created_at?->diffForHumans() }}
                </p>
            </div>
            @if($activity->link ?? null)
            <a href="{{ $activity->link }}" class="flex-shrink-0 text-emerald-600 hover:text-emerald-700 dark:text-emerald-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
