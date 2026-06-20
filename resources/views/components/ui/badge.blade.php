@props([
    'variant' => 'primary',
    'size' => 'md',
    'dot' => false,
])

@php
$colorMap = [
    'primary'     => 'bg-primary-100 text-primary-900',
    'secondary'   => 'bg-slate-100 text-slate-700',
    'success'     => 'bg-success-light text-green-800',
    'warning'     => 'bg-warning-light text-amber-800',
    'destructive' => 'bg-destructive-light text-red-800',
    'info'        => 'bg-info-light text-sky-800',
    'accent'      => 'bg-accent-light text-amber-900',
    'muted'       => 'bg-slate-100 text-slate-500',
];

$sizeMap = [
    'sm' => 'text-[10px] px-1.5 py-0.5',
    'md' => 'text-caption px-2 py-0.5',
    'lg' => 'text-body px-2.5 py-1',
];

$dotColorMap = [
    'primary'     => 'bg-primary',
    'secondary'   => 'bg-slate-500',
    'success'     => 'bg-success',
    'warning'     => 'bg-warning',
    'destructive' => 'bg-destructive',
    'info'        => 'bg-info',
    'accent'      => 'bg-accent',
    'muted'       => 'bg-slate-500',
];

$classes = implode(' ', [
    'inline-flex items-center gap-1 font-medium rounded-full whitespace-nowrap',
    $colorMap[$variant] ?? $colorMap['primary'],
    $sizeMap[$size] ?? $sizeMap['md'],
]);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColorMap[$variant] ?? 'bg-primary' }}"></span>
    @endif
    {{ $slot }}
</span>
