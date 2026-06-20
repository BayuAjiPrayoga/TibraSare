@props([
    'label' => null,
    'error' => null,
    'helper' => null,
    'icon' => null,
    'type' => 'text',
    'required' => false,
    'containerClass' => '',
])

@php
$inputId = $attributes->get('id', $attributes->get('name', 'input-' . uniqid()));
@endphp

<div class="flex flex-col gap-1.5 {{ $containerClass }}">
    @if($label)
        <label for="{{ $inputId }}" class="text-caption font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-destructive ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <x-icon :name="$icon" class="h-4 w-4 text-slate-400" />
            </div>
        @endif

        <input
            id="{{ $inputId }}"
            type="{{ $type }}"
            @if($required) required @endif
            @if($error) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
            {{ $attributes->merge([
                'class' => implode(' ', array_filter([
                    'w-full h-10 rounded-md border bg-white px-3 text-body text-slate-900',
                    'placeholder:text-slate-400',
                    'transition-colors duration-150',
                    'focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none',
                    'disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed',
                    $icon ? 'pl-10' : '',
                    $error ? 'border-destructive focus:border-destructive focus:ring-destructive/20' : 'border-slate-300',
                ]))
            ]) }}
        />
    </div>

    @if($error)
        <p id="{{ $inputId }}-error" class="text-caption text-destructive" role="alert">{{ $error }}</p>
    @elseif($helper)
        <p class="text-caption text-muted-foreground">{{ $helper }}</p>
    @endif
</div>
