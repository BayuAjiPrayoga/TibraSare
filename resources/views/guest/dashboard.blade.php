<x-layouts.app>
    <x-slot name="title">Dashboard Tamu</x-slot>

    @php $user = auth()->user(); @endphp

    {{-- Welcome Header --}}
    <div class="mb-8 pt-4">
        <p class="text-sm font-medium text-slate-500 uppercase tracking-wider mb-1">Selamat Datang,</p>
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">{{ explode(' ', $user->name)[0] }} 👋</h1>
    </div>

    {{-- Active Reservation Highlight (if any) --}}
    @php
        $activeReservations = collect($reservations)->filter(fn($r) => in_array($r['status'], ['reserved', 'checked_in']))->values();
    @endphp

    @if($activeReservations->isNotEmpty())
        <div class="mb-10">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Perjalanan Anda Berikutnya</h2>
            @foreach($activeReservations as $res)
                {{-- Ticket-like Card --}}
                <div class="lg:max-w-3xl bg-gradient-to-br from-primary-900 to-primary-800 rounded-3xl overflow-hidden shadow-xl text-white relative mb-6">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <x-icon name="hotel" class="w-24 h-24" />
                    </div>
                    <div class="p-6 relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="inline-block whitespace-nowrap px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/20 uppercase tracking-widest backdrop-blur-sm">
                                    {{ $res['status'] === 'checked_in' ? 'Sedang Menginap' : 'Akan Datang' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-white/70 font-mono mb-0.5">KODE BOOKING</p>
                                <p class="text-lg font-bold tracking-wider">{{ $res['booking_code'] }}</p>
                            </div>
                        </div>

                        <h3 class="text-2xl font-cormorant font-bold mb-1">{{ $res['room']['category']['name'] }}</h3>
                        <p class="text-primary-100 text-sm mb-6">Kamar {{ $res['room']['room_number'] }}</p>

                        <div class="grid grid-cols-2 gap-4 border-t border-white/10 pt-4">
                            <div>
                                <p class="text-[11px] text-white/60 uppercase tracking-wider mb-1">Check-In</p>
                                <p class="font-semibold">{{ date('d M Y', strtotime($res['check_in_date'])) }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] text-white/60 uppercase tracking-wider mb-1">Check-Out</p>
                                <p class="font-semibold">{{ date('d M Y', strtotime($res['check_out_date'])) }}</p>
                            </div>
                        </div>
                    </div>
                    @if($res['status'] === 'reserved')
                        <div class="bg-white/10 backdrop-blur-md px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="w-full sm:w-auto">
                                @if($res['payment_status'] === 'PAID')
                                    <div class="flex items-center gap-1.5 text-green-300 text-sm font-semibold">
                                        <x-icon name="check-circle" class="w-4 h-4" /> Lunas
                                    </div>
                                @else
                                    <div class="flex items-center gap-1.5 text-amber-300 text-sm font-semibold">
                                        <x-icon name="clock" class="w-4 h-4" /> Menunggu Pembayaran
                                    </div>
                                @endif
                            </div>
                            <div class="w-full sm:w-auto flex justify-end">
                                @if($res['payment_status'] === 'UNPAID' && $res['payment_url'])
                                    <a href="{{ $res['payment_url'] }}" target="_blank" class="w-full sm:w-auto text-center bg-white text-primary-900 px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:scale-105 transition-transform">
                                        Bayar Sekarang
                                    </a>
                                @else
                                    <button onclick="document.getElementById('modal-qr-{{ $res['id'] }}').showModal()" class="w-full sm:w-auto justify-center bg-white/20 hover:bg-white/30 transition text-white px-6 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2">
                                        <x-icon name="qr-code" class="w-4 h-4" /> Tampilkan QR
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- QR Modal --}}
                <dialog id="modal-qr-{{ $res['id'] }}" class="backdrop:bg-slate-900/60 p-0 rounded-3xl shadow-2xl overflow-hidden m-auto max-w-sm w-[90%] sm:w-full">
                    <div class="bg-white p-8 text-center" x-data="{
                        init() {
                            this.$nextTick(() => {
                                new QRCode(this.$refs.qr, {
                                    text: '{{ $res['booking_code'] }}',
                                    width: 200,
                                    height: 200,
                                    colorDark : '#0F172A',
                                    colorLight : '#ffffff',
                                });
                            });
                        }
                    }">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Check-In QR Code</h3>
                        <p class="text-slate-500 text-sm mb-8">Tunjukkan QR code ini kepada resepsionis saat kedatangan Anda.</p>
                        <div class="flex justify-center mb-8">
                            <div class="p-4 bg-white border-2 border-slate-100 rounded-2xl shadow-sm" x-ref="qr"></div>
                        </div>
                        <p class="font-mono text-lg font-bold tracking-widest text-primary-700 mb-8">{{ $res['booking_code'] }}</p>
                        <button onclick="document.getElementById('modal-qr-{{ $res['id'] }}').close()" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-colors">
                            Tutup
                        </button>
                    </div>
                </dialog>
            @endforeach
        </div>
    @else
        <div class="lg:max-w-3xl mb-10 text-center py-12 px-4 bg-white rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-50 to-white"></div>
            <div class="relative z-10">
                <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <x-icon name="hotel" class="w-10 h-10 text-primary-600" />
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Belum ada pesanan aktif</h3>
                <p class="text-slate-500 mb-8 max-w-sm mx-auto">Waktunya merencanakan liburan impian Anda bersama keluarga tercinta.</p>
                <a href="#rooms" class="inline-block bg-primary hover:bg-primary-800 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-primary/30 transition-all hover:-translate-y-0.5">
                    Cari Kamar Sekarang
                </a>
            </div>
        </div>
    @endif

    {{-- Horizontal Scroll for Room Exploration --}}
    <div id="rooms" class="mb-12">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-slate-900">Eksplorasi Kamar</h2>
        </div>
        
        <div class="flex overflow-x-auto pb-6 -mx-4 px-4 sm:mx-0 sm:px-0 gap-4 lg:gap-6 snap-x hide-scrollbar">
            @forelse($roomCategories as $category)
                <div class="min-w-[280px] w-[280px] sm:min-w-[320px] bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden snap-start flex flex-col group hover:shadow-md transition-all">
                    <div class="h-44 relative overflow-hidden bg-slate-100">
                        @if($category->image_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($category->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                        @else
                            <div class="w-full h-full flex items-center justify-center"><x-icon name="image" class="w-8 h-8 text-slate-300" /></div>
                        @endif
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-full text-xs font-bold text-slate-900 shadow-sm">
                            {{ format_currency($category->base_price) }}
                        </div>
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-cormorant text-xl font-bold text-slate-900">{{ $category->name }}</h3>
                        </div>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-6 flex-1">{{ $category->description ?: 'Kamar mewah dengan pemandangan alam memukau.' }}</p>
                        <a href="{{ route('guest.rooms.show', $category->id) }}" class="block w-full py-2.5 bg-primary-50 hover:bg-primary-100 text-primary-700 text-center rounded-xl text-sm font-bold transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-8 text-slate-500 bg-slate-50 rounded-2xl border border-slate-100">
                    Kategori kamar belum tersedia.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Past Reservations History (if any) --}}
    @php
        $pastReservations = collect($reservations)->filter(fn($r) => !in_array($r['status'], ['reserved', 'checked_in']))->values();
    @endphp

    @if($pastReservations->isNotEmpty())
        <div class="mb-12">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Riwayat Perjalanan</h2>
            <div class="space-y-4">
                @foreach($pastReservations as $res)
                    <div class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm flex items-center gap-4 hover:border-slate-200 transition-colors">
                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0">
                            <x-icon name="history" class="w-5 h-5 text-slate-400" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-900 truncate">{{ $res['room']['category']['name'] }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">{{ date('d M Y', strtotime($res['check_in_date'])) }} • {{ format_currency($res['total_price']) }}</p>
                        </div>
                        <div>
                            @if($res['status'] === 'checked_out')
                                <span class="px-2.5 py-1 bg-green-50 text-green-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">Selesai</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-50 text-red-700 rounded-lg text-[10px] font-bold uppercase tracking-wider">Batal</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    @endpush

</x-layouts.app>
