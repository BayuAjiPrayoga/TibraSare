<x-layouts.app>
    <x-slot name="title">Dashboard</x-slot>

    @php $user = auth()->user(); @endphp

    <div class="mb-6">
        <h1 class="text-h1 text-slate-900">Selamat Datang, {{ explode(' ', $user->name)[0] }} 👋</h1>
        <p class="text-body text-muted-foreground mt-0.5">Kelola reservasi Anda di sini.</p>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-h3 text-slate-900">Riwayat Pesanan Saya</h2>
        <a href="#rooms">
            <x-ui.button variant="outline" size="sm" class="flex-row-reverse" icon="arrow-right">
                Pesan Kamar Baru
            </x-ui.button>
        </a>
    </div>

    @if(count($reservations) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16" x-data="{
            init() {
                this.$nextTick(() => {
                    document.querySelectorAll('.qr-container').forEach(el => {
                        if(el.dataset.code) {
                            new QRCode(el, {
                                text: el.dataset.code,
                                width: 128,
                                height: 128
                            });
                        }
                    });
                });
            }
        }">
            @foreach($reservations as $res)
                @php
                    $statusColor = match($res['status']) {
                        'reserved' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'checked_in' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'checked_out' => 'bg-green-100 text-green-800 border-green-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                        default => 'bg-slate-100 text-slate-800 border-slate-200'
                    };
                    $statusLabel = match($res['status']) {
                        'reserved' => 'Dipesan',
                        'checked_in' => 'Check-In',
                        'checked_out' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $res['status']
                    };
                @endphp
                <div class="card p-5 hover:shadow-lg transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-primary-50 p-2 rounded-lg text-primary-700">
                            <x-icon name="hotel" class="w-5 h-5" />
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusColor }}">
                            {{ $statusLabel }}
                        </span>
                        @if($res['payment_status'] === 'PAID')
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium border bg-green-100 text-green-800 border-green-200 ml-2">
                                LUNAS
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-semibold text-lg text-slate-900 mb-1">
                                {{ $res['room']['category']['name'] }} (Kamar {{ $res['room']['room_number'] }})
                            </h3>
                            <p class="text-sm text-slate-500 font-mono">ID: {{ $res['booking_code'] }}</p>
                        </div>
                        <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-sm" title="Tunjukkan QR ini saat Check-In">
                            <div class="qr-container w-[128px] h-[128px]" data-code="{{ $res['booking_code'] }}"></div>
                        </div>
                    </div>

                    <div class="space-y-3 bg-slate-50 rounded-lg p-3 text-sm">
                        <div class="flex items-center gap-2 text-slate-600">
                            <x-icon name="calendar-days" class="w-4 h-4 text-slate-400" />
                            <span>{{ date('d M Y', strtotime($res['check_in_date'])) }} s/d {{ date('d M Y', strtotime($res['check_out_date'])) }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-200">
                            <span class="text-slate-500">Total Harga</span>
                            <span class="font-bold text-primary-700">{{ format_currency($res['total_price']) }}</span>
                        </div>
                        
                        @if($res['status'] === 'reserved')
                        <div class="pt-3 mt-3 border-t border-slate-200 text-right">
                            <form action="{{ route('reservations.cancel', $res['id']) }}" method="POST" class="inline-block" onsubmit="return confirmCancel(event)">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center gap-1 transition-colors">
                                    <x-icon name="trash-2" class="w-4 h-4" /> Batalkan
                                </button>
                            </form>
                            @if($res['payment_status'] === 'UNPAID' && $res['payment_url'])
                                <a href="{{ $res['payment_url'] }}" target="_blank" class="text-primary-600 hover:text-primary-800 text-sm font-medium flex items-center gap-1 transition-colors ml-4 inline-flex">
                                    <x-icon name="credit-card" class="w-4 h-4" /> Bayar Sekarang
                                </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16 bg-white rounded-2xl border border-slate-200 shadow-sm mb-16">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <x-icon name="hotel" class="w-8 h-8 text-slate-400" />
            </div>
            <h3 class="text-lg font-medium text-slate-900 mb-2">Belum ada pesanan</h3>
            <p class="text-slate-500 mb-6">Anda belum pernah melakukan pemesanan kamar.</p>
            <a href="#rooms">
                <x-ui.button>Pesan Kamar Sekarang</x-ui.button>
            </a>
        </div>
    @endif

    {{-- Jelajahi Kamar --}}
    <div id="rooms" class="mt-16 mb-8 border-t border-slate-200 pt-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-h3 text-slate-900">Jelajahi Kamar</h2>
                <p class="text-body text-slate-500 mt-1">Temukan kamar yang sesuai untuk kenyamanan Anda berikutnya.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($roomCategories as $category)
                <div class="group rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="relative h-48 overflow-hidden bg-slate-100">
                        @if($category->image_path)
                            <img 
                                src="{{ \Illuminate\Support\Facades\Storage::url($category->image_path) }}" 
                                alt="{{ $category->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            />
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <x-icon name="hotel" class="w-10 h-10 text-slate-300" />
                            </div>
                        @endif
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-2.5 py-1 rounded-full text-xs font-semibold text-primary shadow-sm">
                            Dari {{ format_currency($category->base_price) }}
                        </div>
                        @if($category->available_rooms_count === 0)
                            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center">
                                <div class="bg-white/90 px-4 py-2 rounded-lg font-bold text-slate-900 shadow-lg border border-slate-200 transform -rotate-12">
                                    SOLD OUT
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-cormorant font-bold text-slate-900">{{ $category->name }}</h3>
                            <span class="text-xs font-medium bg-primary-50 text-primary-700 px-2 py-0.5 rounded-full whitespace-nowrap">
                                {{ $category->available_rooms_count }} Tersedia
                            </span>
                        </div>
                        <p class="text-slate-600 text-sm line-clamp-2 mb-4 leading-relaxed">
                            {{ $category->description ?: 'Kamar nyaman dengan fasilitas modern untuk pengalaman menginap yang tak terlupakan bersama keluarga Anda.' }}
                        </p>

                        @if($category->available_rooms_count > 0)
                            <a href="{{ route('book.create', $category->id) }}">
                                <x-ui.button variant="outline" class="w-full border-primary/20 text-primary hover:bg-primary hover:text-white group-hover:border-primary transition-colors">
                                    Pesan Sekarang <x-icon name="arrow-right" class="w-4 h-4 ml-2" />
                                </x-ui.button>
                            </a>
                        @else
                            <button class="w-full py-2 px-4 rounded-md font-medium text-sm transition-colors focus:outline-none bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed" disabled>
                                Kamar Penuh
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-slate-500">
                    Kategori kamar belum tersedia.
                </div>
            @endforelse
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function confirmCancel(e) {
            e.preventDefault();
            const form = e.target;
            
            Swal.fire({
                title: 'Batalkan Reservasi?',
                text: "Tindakan ini tidak dapat dibatalkan. Jadwal kamar Anda akan otomatis dilepas.",
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
