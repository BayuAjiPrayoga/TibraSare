{{-- Modal Blade component using Alpine.js --}}
@props([
    'id' => 'modal-' . uniqid(),
    'title' => null,
    'description' => null,
    'size' => 'md',
    'show' => false,
])

@php
$sizeMap = [
    'sm'   => 'max-w-sm',
    'md'   => 'max-w-md',
    'lg'   => 'max-w-lg',
    'xl'   => 'max-w-xl',
    'full' => 'max-w-3xl',
];
$sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div
    x-data="{ open: {{ $show ? 'true' : 'false' }} }"
    x-on:open-modal-{{ $id }}.window="open = true"
    x-on:close-modal-{{ $id }}.window="open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    @if($title) aria-labelledby="modal-title-{{ $id }}" @endif
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50"
        @click="open = false"
    ></div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full bg-white rounded-xl shadow-xl {{ $sizeClass }}"
    >
        {{-- Header --}}
        @if($title)
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                <div>
                    <h2 id="modal-title-{{ $id }}" class="text-h3 text-slate-900">{{ $title }}</h2>
                    @if($description)
                        <p class="text-caption text-muted-foreground mt-0.5">{{ $description }}</p>
                    @endif
                </div>
                <button
                    @click="open = false"
                    class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
                    aria-label="Tutup"
                >
                    <x-icon name="x" class="h-5 w-5" />
                </button>
            </div>
        @endif

        {{-- Body --}}
        <div class="px-5 py-4">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @if(isset($footer))
            <div class="flex items-center justify-end gap-2 px-5 py-3 border-t border-slate-200 bg-slate-50 rounded-b-xl">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
