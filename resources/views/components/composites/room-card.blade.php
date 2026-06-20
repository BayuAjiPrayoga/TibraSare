@props(['room'])

@php
$statusConfig = config("navigation.room_status.{$room['status']}", config('navigation.room_status.available'));
@endphp

<div {{ $attributes->merge(['class' => 'card w-full h-full p-4 flex flex-col items-center text-center cursor-pointer transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-primary/20 focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2']) }}>
    {{-- Room Icon --}}
    <div class="w-12 h-12 rounded-lg bg-primary-50 flex items-center justify-center mb-2">
        <x-icon name="bed-double" class="h-6 w-6 text-primary-700" />
    </div>

    {{-- Room Number --}}
    <p class="text-h3 text-slate-900">{{ $room['room_number'] }}</p>

    {{-- Category --}}
    <p class="text-caption text-muted-foreground mt-0.5">
        {{ $room['category']['name'] ?? 'Standard' }}
    </p>

    {{-- Price --}}
    <p class="text-caption font-semibold text-slate-700 mt-1 tabular-nums">
        {{ format_currency($room['price']) }}<span class="font-normal text-muted-foreground">/malam</span>
    </p>

    {{-- Status Badge --}}
    <x-ui.badge :variant="$statusConfig['color']" size="sm" :dot="true" class="mt-2">
        {{ $statusConfig['label'] }}
    </x-ui.badge>
</div>
