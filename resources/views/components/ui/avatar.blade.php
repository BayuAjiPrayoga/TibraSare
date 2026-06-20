@props([
    'src' => null,
    'name' => null,
    'size' => 'md',
])

@php
$sizeMap = [
    'sm' => 'h-8 w-8 text-caption',
    'md' => 'h-10 w-10 text-body',
    'lg' => 'h-12 w-12 text-body-lg',
    'xl' => 'h-16 w-16 text-h3',
];

$sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
$initials = get_initials($name);

$imageUrl = $src;
if ($src && !str_starts_with($src, 'http')) {
    $imageUrl = \Illuminate\Support\Facades\Storage::url($src);
}
@endphp

@if($imageUrl)
    <img
        src="{{ $imageUrl }}"
        alt="{{ $name ?? 'Avatar' }}"
        {{ $attributes->merge(['class' => "rounded-full object-cover border-2 border-white shadow-xs $sizeClass"]) }}
    />
@else
    <div
        aria-label="{{ $name ?? 'Avatar' }}"
        {{ $attributes->merge(['class' => "rounded-full flex items-center justify-center font-semibold bg-primary-100 text-primary-800 border-2 border-white shadow-xs $sizeClass"]) }}
    >
        {{ $initials }}
    </div>
@endif
