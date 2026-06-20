@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'iconRight' => null,
    'href' => null,
])

@php
$variants = [
    'primary'     => 'bg-primary text-primary-foreground hover:bg-primary-800 active:bg-primary-900',
    'secondary'   => 'bg-primary-100 text-primary-900 hover:bg-primary-200 active:bg-primary-300',
    'outline'     => 'border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 active:bg-slate-100',
    'ghost'       => 'text-slate-700 hover:bg-slate-100 active:bg-slate-200',
    'destructive' => 'bg-destructive text-destructive-foreground hover:bg-red-700 active:bg-red-800',
    'accent'      => 'bg-accent text-accent-foreground hover:bg-amber-700 active:bg-amber-800',
    'link'        => 'text-primary underline-offset-4 hover:underline p-0 h-auto',
];

$sizes = [
    'sm'   => 'h-8 px-3 text-caption rounded-sm gap-1.5',
    'md'   => 'h-10 px-4 text-body rounded-md gap-2',
    'lg'   => 'h-12 px-6 text-body-lg rounded-md gap-2',
    'xl'   => 'h-14 px-8 text-body-lg rounded-lg gap-2.5',
    'icon' => 'h-10 w-10 rounded-md',
];

$isDisabled = $disabled || $loading;

$classes = implode(' ', array_filter([
    'inline-flex items-center justify-center font-medium',
    'transition-colors duration-200 cursor-pointer',
    'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2',
    'disabled:pointer-events-none disabled:opacity-50',
    $variants[$variant] ?? $variants['primary'],
    $sizes[$size] ?? $sizes['md'],
]));
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-icon :name="$icon" class="h-4 w-4 shrink-0" />
        @endif
        @if($size !== 'icon')
            {{ $slot }}
        @endif
        @if($iconRight)
            <x-icon :name="$iconRight" class="h-4 w-4 shrink-0" />
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $isDisabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        @if($loading)
            <x-icon name="loader-2" class="h-4 w-4 animate-spin" />
        @elseif($icon)
            <x-icon :name="$icon" class="h-4 w-4 shrink-0" />
        @endif
        @if($size !== 'icon')
            {{ $slot }}
        @endif
        @if($iconRight && !$loading)
            <x-icon :name="$iconRight" class="h-4 w-4 shrink-0" />
        @endif
    </button>
@endif
