<x-layouts.app>
    <x-slot name="title">Reservasi</x-slot>

    <div x-data="{ statusFilter: 'all', search: '' }">
        <x-composites.page-header title="Manajemen Reservasi" description="Kelola seluruh reservasi tamu.">
            <x-slot name="action">
                <x-ui.button icon="plus" href="{{ route('reservations.create') }}">Buat Reservasi</x-ui.button>
            </x-slot>
        </x-composites.page-header>

        {{-- Filters --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <form method="GET" action="{{ route('reservations.index') }}" class="flex-1 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode booking atau nama tamu..." class="w-full h-10 rounded-md border border-slate-300 bg-white px-3 text-body text-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" />
                <x-ui.button type="submit" variant="secondary">Cari</x-ui.button>
            </form>
            <div class="flex gap-2 overflow-x-auto pb-1">
                <button @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-primary text-white' : 'bg-white text-slate-600 border border-slate-300'" class="px-3 py-2 rounded-lg text-caption font-medium whitespace-nowrap transition-colors cursor-pointer">Semua</button>
                @foreach(config('navigation.reservation_status') as $key => $cfg)
                    <button @click="statusFilter = '{{ $key }}'" :class="statusFilter === '{{ $key }}' ? 'bg-primary text-white' : 'bg-white text-slate-600 border border-slate-300'" class="px-3 py-2 rounded-lg text-caption font-medium whitespace-nowrap transition-colors cursor-pointer">{{ $cfg['label'] }}</button>
                @endforeach
            </div>
        </div>

        @if(count($reservations) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @foreach($reservations as $res)
                    <div x-show="(statusFilter === 'all' || '{{ $res['status'] }}' === statusFilter)">
                        <x-composites.reservation-card :reservation="$res" />
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $reservations->links() }}
            </div>
        @else
            <x-composites.empty-state icon="calendar-check" title="Belum ada reservasi" description="Reservasi akan muncul di sini setelah dibuat." />
        @endif
    </div>
</x-layouts.app>
