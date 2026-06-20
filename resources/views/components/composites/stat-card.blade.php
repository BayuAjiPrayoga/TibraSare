@props([
    'label',
    'value',
    'icon' => null,
    'color' => 'primary',
    'trend' => null,
    'trendLabel' => null,
])

@php
$iconBgMap = [
    'primary'     => 'bg-primary-100 text-primary-800',
    'success'     => 'bg-success-light text-green-700',
    'warning'     => 'bg-warning-light text-amber-700',
    'destructive' => 'bg-destructive-light text-red-700',
    'info'        => 'bg-info-light text-sky-700',
    'accent'      => 'bg-accent-light text-amber-800',
];
@endphp

<div {{ $attributes->merge(['class' => 'card p-4 flex items-start gap-3']) }}>
    @if($icon)
        <div class="p-2.5 rounded-lg shrink-0 {{ $iconBgMap[$color] ?? $iconBgMap['primary'] }}">
            <x-icon :name="$icon" class="h-5 w-5" />
        </div>
    @endif

    <div class="flex-1 min-w-0">
        <p class="text-caption text-muted-foreground truncate">{{ $label }}</p>
        <p class="text-h1 text-slate-900 tabular-nums mt-0.5">{{ $value }}</p>

        @if(!is_null($trend))
            <p class="text-caption mt-1 flex items-center gap-1 {{ $trend >= 0 ? 'text-green-600' : 'text-red-600' }}">
                <span>{{ $trend >= 0 ? '↑' : '↓' }} {{ abs($trend) }}%</span>
                @if($trendLabel)
                    <span class="text-muted-foreground">{{ $trendLabel }}</span>
                @endif
            </p>
        @endif
    </div>
</div>
