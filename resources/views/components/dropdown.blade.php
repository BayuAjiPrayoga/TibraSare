{{-- Dropdown component using Alpine.js --}}
@props([
    'align' => 'right',
    'width' => '48',
    'contentClasses' => 'py-1 bg-white',
])

@php
$alignClasses = match($align) {
    'left'  => 'origin-top-left start-0',
    'right' => 'origin-top-right end-0',
    default => 'origin-top',
};

$widthClasses = match($width) {
    '48' => 'w-48',
    '64' => 'w-64',
    default => '',
};
@endphp

<div x-data="{ open: false }" @click.outside="open = false" class="relative">
    {{-- Trigger --}}
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    {{-- Content --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-cloak
        @click="open = false"
        class="absolute z-50 mt-2 rounded-md shadow-lg {{ $alignClasses }} {{ $widthClasses }}"
    >
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $slot }}
        </div>
    </div>
</div>
