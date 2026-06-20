@props([
    'label' => null,
    'error' => null,
    'helper' => null,
    'options' => [],
    'placeholder' => 'Pilih...',
    'required' => false,
    'containerClass' => '',
])

@php
$selectId = $attributes->get('id', $attributes->get('name', 'select-' . uniqid()));
@endphp

<div class="flex flex-col gap-1.5 {{ $containerClass }}">
    @if($label)
        <label for="{{ $selectId }}" class="text-caption font-medium text-slate-700">
            {{ $label }}
            @if($required)
                <span class="text-destructive ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <select
            id="{{ $selectId }}"
            @if($required) required @endif
            @if($error) aria-invalid="true" @endif
            {{ $attributes->merge([
                'class' => implode(' ', array_filter([
                    'w-full h-10 rounded-md border bg-white pl-3 pr-10 text-body text-slate-900',
                    'appearance-none cursor-pointer',
                    'transition-colors duration-150',
                    'focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none',
                    'disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed',
                    $error ? 'border-destructive focus:border-destructive focus:ring-destructive/20' : 'border-slate-300',
                ]))
            ]) }}
        >
            @if($placeholder)
                <option value="" disabled>{{ $placeholder }}</option>
            @endif
            @foreach($options as $opt)
                <option value="{{ $opt['value'] }}" @selected(old($attributes->get('name')) == $opt['value'] || $attributes->get('value') == $opt['value'])>
                    {{ $opt['label'] }}
                </option>
            @endforeach
            {{ $slot }}
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <x-icon name="chevron-down" class="h-4 w-4 text-slate-400" />
        </div>
    </div>

    @if($error)
        <p class="text-caption text-destructive" role="alert">{{ $error }}</p>
    @elseif($helper)
        <p class="text-caption text-muted-foreground">{{ $helper }}</p>
    @endif
</div>
