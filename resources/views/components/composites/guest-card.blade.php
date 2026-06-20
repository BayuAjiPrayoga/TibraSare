@props(['guest'])

<div {{ $attributes->merge(['class' => 'card p-4 hover:shadow-md transition-shadow group relative overflow-hidden']) }}>
    {{-- Top Row: Avatar & Actions --}}
    <div class="flex justify-between items-start mb-3">
        <div class="flex gap-3 items-center">
            <x-ui.avatar :name="$guest['full_name']" size="md" />
            <div>
                <h3 class="text-body font-semibold text-slate-900 line-clamp-1">{{ $guest['full_name'] }}</h3>
                @if(($guest['status'] ?? null) === 'in_house')
                    <x-ui.badge variant="success" size="sm">Sedang Menginap</x-ui.badge>
                @else
                    <x-ui.badge variant="secondary" size="sm">Tamu Reguler</x-ui.badge>
                @endif
            </div>
        </div>

        {{-- Desktop Actions --}}
        @if(isset($actions))
            <div class="hidden sm:flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                {{ $actions }}
            </div>
        @endif
    </div>

    {{-- Details --}}
    <div class="space-y-2">
        <div class="flex items-center gap-2 text-caption text-slate-600">
            <x-icon name="mail" class="w-3.5 h-3.5 text-slate-400 shrink-0" />
            <span class="truncate">{{ $guest['email'] }}</span>
        </div>
        <div class="flex items-center gap-2 text-caption text-slate-600">
            <x-icon name="phone" class="w-3.5 h-3.5 text-slate-400 shrink-0" />
            <span>{{ $guest['phone'] }}</span>
        </div>
    </div>

    {{-- Bottom Row --}}
    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-caption text-slate-500">
        <div class="flex items-center gap-1.5">
            <x-icon name="calendar-days" class="w-3.5 h-3.5" />
            <span>Terakhir: {{ isset($guest['last_stay']) ? format_date_id($guest['last_stay']) : 'Belum pernah' }}</span>
        </div>
        <div>
            <span class="font-semibold text-slate-700">{{ $guest['total_visits'] ?? 0 }}</span> Kunjungan
        </div>
    </div>
</div>
