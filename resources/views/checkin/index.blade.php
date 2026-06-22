<x-layouts.app>
    <x-slot name="title">Check-In</x-slot>

    <div x-data="{
        search: '',
        showConfirm: false,
        selectedReservation: null,
        reservations: @js($todayReservations),
        
        get filteredReservations() {
            if (this.search === '') return this.reservations;
            const q = this.search.toLowerCase();
            return this.reservations.filter(r => 
                (r.booking_code && r.booking_code.toLowerCase().includes(q)) ||
                (r.guest && r.guest.full_name && r.guest.full_name.toLowerCase().includes(q))
            );
        },
        
        openConfirm(reservation) {
            this.selectedReservation = reservation;
            this.showConfirm = true;
            this.$nextTick(() => {
                document.getElementById('qrcode-container').innerHTML = '';
                new QRCode(document.getElementById('qrcode-container'), {
                    text: reservation.booking_code,
                    width: 100,
                    height: 100
                });
            });
        },
        
        handleScanSuccess(text) {
            this.search = text;
            const match = this.reservations.find(r => r.booking_code === text);
            if (match) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url('check-in') }}/' + match.id;
                
                const csrfToken = '{{ csrf_token() }}';
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;
                
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            } else {
                alert('Kode QR tidak ditemukan di daftar Check-In hari ini.');
            }
        },

        calculateNights(inDate, outDate) {
            const start = new Date(inDate);
            const end = new Date(outDate);
            const diff = Math.max(0, end - start);
            return Math.ceil(diff / (1000 * 60 * 60 * 24)) || 1;
        },

        formatDate(dateStr) {
            return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        },

        formatCurrency(val) {
            return 'Rp ' + parseInt(val).toLocaleString('id-ID');
        }
    }" @qr-scanned.window="handleScanSuccess($event.detail.text)">

        <x-composites.page-header title="Check-In" description="Proses check-in tamu yang sudah memiliki reservasi hari ini." />

        {{-- Search Bar --}}
        <div class="mb-6 flex gap-2">
            <div class="flex-1">
                <x-ui.input placeholder="Cari kode booking atau nama tamu..." icon="search" x-model="search" />
            </div>
            <x-ui.button variant="outline" size="icon" icon="qr-code" aria-label="Scan QR Code" @click="$dispatch('open-qr-scanner')"></x-ui.button>
        </div>


        @if(count($todayReservations) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($todayReservations as $res)
                    <div x-show="!search || '{{ strtolower($res['booking_code'] . ' ' . $res['guest']['full_name']) }}'.includes(search.toLowerCase())">
                        <x-composites.reservation-card :reservation="$res">
                            <x-slot name="actions">
                                <x-ui.button type="button" size="sm" icon="log-in" @click.stop="openConfirm({{ json_encode($res) }})">Check-In</x-ui.button>
                            </x-slot>
                        </x-composites.reservation-card>
                    </div>
                @endforeach
            </div>
        @else
            <x-composites.empty-state icon="log-in" title="Tidak ada check-in hari ini" description="Belum ada reservasi yang siap untuk di-check-in." />
        @endif

        {{-- Confirmation Modal --}}
        <div x-show="showConfirm" x-cloak class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4">
            <div x-show="showConfirm" x-transition class="fixed inset-0 bg-black/50" @click="showConfirm = false"></div>
            <div x-show="showConfirm" x-transition class="relative w-full max-w-sm bg-white rounded-xl shadow-xl flex flex-col">
                <div class="px-5 py-4 border-b border-slate-200">
                    <h2 class="text-h3 text-slate-900">Konfirmasi Check-In</h2>
                </div>
                
                <template x-if="selectedReservation">
                    <div class="p-5 space-y-4">
                        <div class="flex justify-center p-4 bg-slate-50 rounded-lg">
                            <div class="p-2 bg-white rounded shadow-sm">
                                <div id="qrcode-container"></div>
                            </div>
                        </div>

                        <div class="p-4 rounded-lg bg-slate-50 space-y-3">
                            <div class="flex items-center gap-2">
                                <x-icon name="user" class="h-4 w-4 text-muted-foreground" />
                                <span class="text-body font-medium text-slate-900" x-text="selectedReservation.guest?.full_name"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="bed-double" class="h-4 w-4 text-muted-foreground" />
                                <span class="text-body text-slate-700">Kamar <span x-text="selectedReservation.room?.room_number"></span> · <span x-text="selectedReservation.room?.category?.name"></span></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-icon name="calendar-days" class="h-4 w-4 text-muted-foreground" />
                                <span class="text-body text-slate-700" x-text="formatDate(selectedReservation.check_in_date) + ' — ' + formatDate(selectedReservation.check_out_date)"></span>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-slate-200">
                                <span class="text-caption text-muted-foreground" x-text="calculateNights(selectedReservation.check_in_date, selectedReservation.check_out_date) + ' malam'"></span>
                                <span class="text-body font-semibold text-slate-900 tabular-nums" x-text="formatCurrency(selectedReservation.total_price)"></span>
                            </div>
                        </div>

                        <p class="text-caption text-muted-foreground text-center">
                            Kamar akan otomatis berubah menjadi <x-ui.badge variant="destructive" size="sm">Terisi</x-ui.badge>
                        </p>
                    </div>
                </template>

                <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-slate-200">
                    <x-ui.button variant="outline" type="button" @click="showConfirm = false">Batal</x-ui.button>
                    <form method="POST" :action="'{{ url('check-in') }}/' + selectedReservation?.id">
                        @csrf
                        <x-ui.button type="submit" icon="check-circle">Proses Check-In</x-ui.button>
                    </form>
                </div>
            </div>
        </div>

        <x-scan-qr-modal />
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @endpush
</x-layouts.app>
