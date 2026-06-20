@props(['reservation'])

@php
$statusConfig = config("navigation.reservation_status.{$reservation['status']}", config('navigation.reservation_status.reserved'));
@endphp

<a href="{{ route('reservations.show', $reservation['id']) }}" class="block group">
<div {{ $attributes->merge(['class' => 'card p-4 transition-all duration-300 group-hover:shadow-md group-hover:-translate-y-1 group-hover:border-primary/20']) }}>
    {{-- Top Row: Booking Code + Status --}}
    <div class="flex items-center justify-between mb-3">
        <span class="text-caption font-semibold text-primary tabular-nums">
            #{{ $reservation['booking_code'] }}
        </span>
        <x-ui.badge :variant="$statusConfig['color']" size="sm" :dot="true">
            {{ $statusConfig['label'] }}
        </x-ui.badge>
    </div>

    {{-- Guest Name --}}
    <p class="text-h3 text-slate-900 truncate">
        {{ $reservation['guest']['full_name'] ?? 'Tamu' }}
    </p>

    {{-- Room Info --}}
    <div class="flex items-center gap-1.5 mt-1.5 text-caption text-muted-foreground">
        <x-icon name="bed-double" class="h-3.5 w-3.5 shrink-0" />
        <span>
            {{ $reservation['room']['room_number'] ?? '-' }} · {{ $reservation['room']['category']['name'] ?? 'Standard' }}
        </span>
    </div>

    {{-- Date Range --}}
    <div class="flex items-center gap-1.5 mt-1 text-caption text-muted-foreground">
        <x-icon name="calendar-check" class="h-3.5 w-3.5 shrink-0" />
        <span>
            {{ format_date_short($reservation['check_in_date']) }} — {{ format_date_short($reservation['check_out_date']) }}
        </span>
    </div>

    {{-- Bottom Row: Price + Actions --}}
    <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
        <span class="text-body font-semibold text-slate-900 tabular-nums">
            {{ format_currency($reservation['total_price']) }}
        </span>

        @if(isset($actions))
            <div class="flex gap-2">{{ $actions }}</div>
        @else
            <x-icon name="chevron-right" class="h-4 w-4 text-slate-400" />
        @endif
    </div>
</div>
</a>
