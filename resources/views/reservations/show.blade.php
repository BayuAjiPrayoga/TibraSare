<x-layouts.app>
    <x-slot name="title">Detail Reservasi #{{ $reservation->booking_code }}</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('reservations.index') }}" class="text-slate-500 hover:text-slate-900 transition-colors">
                    <x-icon name="arrow-left" class="w-5 h-5" />
                </a>
                <h1 class="text-h2 text-slate-900">Detail Reservasi</h1>
            </div>
            <p class="text-slate-500 ml-7">#{{ $reservation->booking_code }}</p>
        </div>
        
        <div class="flex gap-2">
            @if($reservation->status === \App\Enums\ReservationStatus::Reserved)
                <form action="{{ route('reservations.cancel', $reservation->id) }}" method="POST" onsubmit="return confirmAdminCancel(event)">
                    @csrf
                    <x-ui.button type="submit" variant="destructive" icon="x-circle">Batalkan Reservasi</x-ui.button>
                </form>
                @if($reservation->payment_status === 'UNPAID' && $reservation->payment_url)
                    <a href="{{ $reservation->payment_url }}" target="_blank">
                        <x-ui.button variant="primary" icon="credit-card">Bayar Sekarang</x-ui.button>
                    </a>
                @endif
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            {{-- Data Tamu --}}
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <x-icon name="user" class="w-5 h-5 text-primary" /> Data Tamu
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Nama Lengkap</p>
                        <p class="font-medium text-slate-900">{{ $reservation->guest->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Nomor Telepon</p>
                        <p class="font-medium text-slate-900">{{ $reservation->guest->phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Email</p>
                        <p class="font-medium text-slate-900">{{ $reservation->guest->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Identitas ({{ $reservation->guest->identity_type }})</p>
                        <p class="font-medium text-slate-900">{{ $reservation->guest->identity_number }}</p>
                    </div>
                </div>
            </div>

            {{-- Data Kamar --}}
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center gap-2">
                    <x-icon name="bed-double" class="w-5 h-5 text-primary" /> Detail Kamar
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-slate-500">Nomor Kamar</p>
                        <p class="font-medium text-slate-900">{{ $reservation->room->room_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Tipe Kamar</p>
                        <p class="font-medium text-slate-900">{{ $reservation->room->category->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Check-In</p>
                        <p class="font-medium text-slate-900">{{ $reservation->check_in_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Check-Out</p>
                        <p class="font-medium text-slate-900">{{ $reservation->check_out_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Durasi Menginap</p>
                        <p class="font-medium text-slate-900">{{ $reservation->nights }} Malam</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Status --}}
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Status</h3>
                @php
                    $statusConfig = config("navigation.reservation_status.{$reservation->status->value}", config('navigation.reservation_status.reserved'));
                @endphp
                <div class="mb-4">
                    <x-ui.badge :variant="$statusConfig['color']" size="lg" :dot="true">
                        {{ $statusConfig['label'] }}
                    </x-ui.badge>
                </div>
                
                <div class="pt-4 border-t border-slate-100 mb-4">
                    <p class="text-sm text-slate-500 mb-1">Total Tagihan</p>
                    <p class="text-2xl font-bold text-primary-700">{{ format_currency($reservation->total_price) }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-700">Status Pembayaran</p>
                    @if($reservation->payment_status === 'PAID')
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium border bg-green-100 text-green-800 border-green-200">
                            LUNAS
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium border bg-yellow-100 text-yellow-800 border-yellow-200">
                            BELUM LUNAS
                        </span>
                    @endif
                </div>
            </div>

            {{-- Dibuat Oleh --}}
            <div class="card p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Informasi Tambahan</h3>
                <p class="text-sm text-slate-600 mb-1">Dibuat Pada: {{ $reservation->created_at->format('d M Y H:i') }}</p>
                <p class="text-sm text-slate-600">Dibuat Oleh: {{ $reservation->creator->name ?? 'Sistem' }}</p>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        function confirmAdminCancel(e) {
            e.preventDefault();
            const form = e.target;
            
            Swal.fire({
                title: 'Batalkan Reservasi?',
                text: "Tindakan ini tidak dapat dibatalkan. Status kamar akan dikembalikan menjadi Tersedia.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-layouts.app>
