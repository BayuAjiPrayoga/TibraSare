@props([
    'label',
    'icon',
    'href' => null,
    'color' => 'primary',
])

@php
$colorMap = [
    'primary'     => 'bg-primary-50 text-primary-700 hover:bg-primary-100',
    'success'     => 'bg-success-light text-green-700 hover:bg-green-100',
    'warning'     => 'bg-warning-light text-amber-700 hover:bg-amber-100',
    'destructive' => 'bg-destructive-light text-red-700 hover:bg-red-100',
    'info'        => 'bg-info-light text-sky-700 hover:bg-sky-100',
];

$baseClass = implode(' ', [
    'flex flex-col items-center p-3 rounded-xl transition-colors duration-200 cursor-pointer',
    'focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2',
    $colorMap[$color] ?? $colorMap['primary'],
]);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClass]) }}>
        <div class="p-2 rounded-lg bg-white/60 shadow-xs">
            <x-icon :name="$icon" class="h-5 w-5" />
        </div>
        <span class="text-caption font-medium mt-1.5">{{ $label }}</span>
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => $baseClass]) }}>
        <div class="p-2 rounded-lg bg-white/60 shadow-xs">
            <x-icon :name="$icon" class="h-5 w-5" />
        </div>
        <span class="text-caption font-medium mt-1.5">{{ $label }}</span>
    </button>
@endif
