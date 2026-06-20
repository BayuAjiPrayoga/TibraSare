<x-layouts.app>
    <x-slot name="title">Check-Out</x-slot>

    <div x-data="{
        search: '',
        showConfirm: false,
        selectedReservation: null,
        reservations: @js($todayCheckOuts),
        
        get filteredReservations() {
            if (this.search === '') return this.reservations;
            const q = this.search.toLowerCase();
            return this.reservations.filter(r => 
                (r.booking_code && r.booking_code.toLowerCase().includes(q)) ||
                (r.guest && r.guest.full_name && r.guest.full_name.toLowerCase().includes(q)) ||
                (r.room && r.room.room_number && String(r.room.room_number).includes(q))
            );
        },
        
        openConfirm(reservation) {
            this.selectedReservation = reservation;
            this.showConfirm = true;
        },
        
        handleScanSuccess(text) {
            this.search = text;
            const match = this.reservations.find(r => r.booking_code === text);
            if (match) {
                this.openConfirm(match);
            }
        }
    }" @qr-scanned.window="handleScanSuccess($event.detail.text)">

        <x-composites.page-header title="Check-Out" description="Proses check-out tamu yang sudah selesai menginap." />

        {{-- Search Bar --}}
        <div class="mb-6 flex gap-2">
            <div class="flex-1">
                <x-ui.input placeholder="Cari kode booking, nama tamu, atau no. kamar..." icon="search" x-model="search" />
            </div>
            <x-ui.button variant="outline" size="icon" icon="qr-code" aria-label="Scan QR Code" @click="$dispatch('open-qr-scanner')"></x-ui.button>
        </div>

        @if(count($todayCheckOuts) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($todayCheckOuts as $res)
                    <div x-show="!search || '{{ strtolower($res['booking_code'] . ' ' . $res['guest']['full_name']) }}'.includes(search.toLowerCase()) || '{{ $res['room']['room_number'] }}'.includes(search)">
                        <x-composites.reservation-card :reservation="$res">
                            <x-slot name="actions">
                                <x-ui.button type="button" variant="accent" size="sm" icon="log-out" @click.stop="openConfirm({{ json_encode($res) }})">Check-Out</x-ui.button>
                            </x-slot>
                        </x-composites.reservation-card>
                    </div>
                @endforeach
            </div>
        @else
            <x-composites.empty-state icon="log-out" title="Tidak ada check-out hari ini" description="Belum ada tamu yang perlu di-check-out." />
        @endif

        {{-- Confirmation Modal --}}
        <div x-show="showConfirm" x-cloak class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4">
            <div x-show="showConfirm" x-transition class="fixed inset-0 bg-black/50" @click="showConfirm = false"></div>
            <div x-show="showConfirm" x-transition class="relative w-full max-w-sm bg-white rounded-xl shadow-xl flex flex-col">
                <div class="px-5 py-4 border-b border-slate-200">
                    <h2 class="text-h3 text-slate-900">Konfirmasi Check-Out</h2>
                </div>
                
                <template x-if="selectedReservation">
                    <div class="p-5 space-y-4">
                        <p class="text-body text-slate-700 text-center">
                            Anda yakin ingin memproses check-out untuk kamar <strong x-text="selectedReservation.room?.room_number"></strong> (Tamu: <strong x-text="selectedReservation.guest?.full_name"></strong>)?
                        </p>
                    </div>
                </template>

                <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-slate-200">
                    <x-ui.button variant="outline" type="button" @click="showConfirm = false">Batal</x-ui.button>
                    <form method="POST" :action="'{{ url('check-out') }}/' + selectedReservation?.id">
                        @csrf
                        <x-ui.button type="submit" icon="check-circle" variant="accent">Proses Check-Out</x-ui.button>
                    </form>
                </div>
            </div>
        </div>

        <x-scan-qr-modal />
    </div>
</x-layouts.app>
