<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tibra Sare — Luxury Resort & Spa</title>
    <meta name="description" content="Rasakan pengalaman menginap tak terlupakan di jantung kota dengan layanan premium dan pemandangan menakjubkan.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- PWA Configuration -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/IconTS.png') }}">
    <meta name="theme-color" content="#0F172A">

    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased" x-data="pwaSetup()">
<div class="min-h-screen bg-[#F8FAFC] text-slate-800 relative">

    {{-- Navbar --}}
    <nav class="absolute top-0 inset-x-0 z-50 bg-transparent border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-20">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 group-hover:bg-white/20 transition-all overflow-hidden">
                    <img src="/images/IconTS.png" alt="Tibra Sare Logo" class="w-full h-full object-contain" />
                </div>
                <div>
                    <span class="text-xl font-bold text-white tracking-wide uppercase block leading-none">Tibra Sare</span>
                    <span class="text-[10px] text-white/70 tracking-[0.2em] uppercase">Resort & Spa</span>
                </div>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}">
                        <x-ui.button class="bg-accent hover:bg-amber-600 text-white border-0 shadow-lg shadow-accent/20 rounded-full px-6">Dasbor Saya</x-ui.button>
                    </a>
                @else
                    @if($canLogin)<a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-amber-200 transition-colors">Login</a>@endif
                    @if($canRegister)<a href="{{ route('register') }}"><x-ui.button class="bg-accent hover:bg-amber-600 text-white border-0 shadow-lg shadow-accent/20 rounded-full px-6">Daftar</x-ui.button></a>@endif
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative min-h-[90vh] flex items-center justify-center pt-20 pb-32">
        <div class="absolute inset-0 z-0">
            <img src="/images/hero.png" alt="Tibra Sare Luxury Resort" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-b from-primary-900/80 via-primary-900/60 to-primary-900/90"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center mt-12">
            <div class="flex items-center gap-2 mb-6">
                @for($i = 0; $i < 5; $i++)
                    <svg class="w-4 h-4 text-amber-300 fill-amber-300" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                @endfor
            </div>
            <h1 class="text-5xl sm:text-6xl md:text-7xl font-bold text-white leading-tight max-w-4xl tracking-tight">
                A Symphony of <span class="text-amber-200 italic font-normal">Elegance</span> & Comfort
            </h1>
            <p class="mt-6 text-lg text-white/80 max-w-2xl font-light leading-relaxed">
                Rasakan pengalaman menginap tak terlupakan di jantung kota dengan layanan premium dan pemandangan menakjubkan.
            </p>

            {{-- Booking Widget --}}
            <div class="mt-12 w-full max-w-4xl bg-white/10 backdrop-blur-md border border-white/20 p-2 rounded-2xl shadow-2xl">
                <form class="flex flex-col md:flex-row bg-white rounded-xl overflow-hidden p-2">
                    <div class="flex-1 flex items-center px-4 py-3 md:py-0 border-b md:border-b-0 md:border-r border-slate-200">
                        <x-icon name="calendar-check" class="w-5 h-5 text-amber-600 mr-3" />
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Check-In</label>
                            <input type="date" class="w-full border-0 p-0 text-sm focus:ring-0 text-slate-900 font-medium" />
                        </div>
                    </div>
                    <div class="flex-1 flex items-center px-4 py-3 md:py-0 border-b md:border-b-0 md:border-r border-slate-200">
                        <x-icon name="calendar-check" class="w-5 h-5 text-amber-600 mr-3" />
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Check-Out</label>
                            <input type="date" class="w-full border-0 p-0 text-sm focus:ring-0 text-slate-900 font-medium" />
                        </div>
                    </div>
                    <div class="flex-1 flex items-center px-4 py-3 md:py-0">
                        <x-icon name="users" class="w-5 h-5 text-amber-600 mr-3" />
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tamu</label>
                            <select class="w-full border-0 p-0 text-sm focus:ring-0 text-slate-900 font-medium bg-transparent">
                                <option>1 Dewasa</option><option>2 Dewasa</option><option>2 Dewasa, 1 Anak</option><option>3 Dewasa</option>
                            </select>
                        </div>
                    </div>
                    <div class="p-2 md:p-0 md:pl-2">
                        <a href="#rooms" class="flex items-center justify-center w-full h-full py-4 bg-primary hover:bg-primary-800 text-white rounded-lg whitespace-nowrap px-8 font-medium transition-colors">Cek Ketersediaan</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Room Showcase --}}
    <section id="rooms" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="text-amber-600 text-sm font-semibold tracking-widest uppercase mb-2 block">Akomodasi</span>
                <h2 class="text-4xl md:text-5xl font-bold text-primary-900">Kamar & Suite Mewah</h2>
                <p class="mt-4 text-slate-600 max-w-2xl mx-auto">Setiap kamar dirancang secara eksklusif dengan sentuhan modern dan fasilitas premium untuk kenyamanan istirahat Anda.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($roomCategories as $category)
                    <div class="group rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="relative h-64 overflow-hidden">
                            @if($category->image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center"><x-icon name="bed-double" class="w-12 h-12 text-slate-400" /></div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-full text-xs font-semibold text-primary shadow-sm">Dari {{ format_currency($category->base_price) }}</div>
                            @if($category->available_rooms_count === 0)
                                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center">
                                    <div class="bg-white/90 px-4 py-2 rounded-lg font-bold text-slate-900 shadow-lg border border-slate-200 transform -rotate-12">SOLD OUT</div>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-2xl font-bold text-slate-900">{{ $category->name }}</h3>
                                <span class="text-xs font-medium bg-primary-50 text-primary-700 px-2.5 py-1 rounded-full whitespace-nowrap">{{ $category->available_rooms_count }} Tersedia</span>
                            </div>
                            <p class="text-slate-600 text-sm line-clamp-2 mb-4 leading-relaxed">{{ $category->description ?: 'Kamar nyaman dengan fasilitas modern untuk pengalaman menginap yang tak terlupakan.' }}</p>
                            @if($category->available_rooms_count > 0)
                                <a href="{{ route('book.create', $category->id) }}" class="flex items-center justify-center w-full py-2.5 rounded-lg border border-primary/20 text-primary font-medium hover:bg-primary hover:text-white transition-colors">
                                    Pesan Sekarang →
                                </a>
                            @else
                                <button disabled class="w-full py-2.5 rounded-lg bg-slate-100 text-slate-400 border border-slate-200 cursor-not-allowed">Kamar Penuh</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-500">Kategori kamar belum tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Facilities Section --}}
    <section class="py-24 bg-primary-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-amber-200 text-sm font-semibold tracking-widest uppercase mb-2 block">Fasilitas Resort</span>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Pengalaman Bintang Lima</h2>
                    <p class="text-white/70 text-lg font-light leading-relaxed mb-8">Nikmati berbagai fasilitas kelas dunia yang dirancang khusus untuk memanjakan diri Anda.</p>
                    <div class="grid grid-cols-2 gap-6">
                        @foreach([['Fine Dining', 'Restoran & Bar Mewah'], ['Wellness Spa', 'Pijat & Terapi Relaksasi'], ['High-Speed WiFi', 'Koneksi Cepat di Seluruh Area'], ['Smart Room', 'Kontrol Suhu & Hiburan']] as $fac)
                            <div class="flex gap-4">
                                <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center shrink-0"><x-icon name="wifi" class="w-5 h-5 text-amber-200" /></div>
                                <div>
                                    <h4 class="font-semibold text-white mb-1">{{ $fac[0] }}</h4>
                                    <p class="text-sm text-white/60">{{ $fac[1] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-primary-800 shadow-2xl relative">
                        <img src="/images/hero.png" alt="Facilities" class="w-full h-full object-cover opacity-60 hover:opacity-100 transition-all duration-700" />
                        <div class="absolute inset-0 border-2 border-white/10 rounded-2xl m-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-[#0F172A] text-slate-400 py-16 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-24 mb-12">
                <div>
                    <a href="/" class="flex items-center gap-2 mb-6">
                        <div class="w-8 h-8 overflow-hidden flex items-center justify-center"><img src="/images/IconTS.png" alt="Logo" class="w-full h-full object-contain" /></div>
                        <span class="text-2xl font-bold text-white tracking-wide uppercase">Tibra Sare</span>
                    </a>
                    <p class="text-sm leading-relaxed text-slate-400 mb-6 max-w-sm">Destinasi kemewahan dan ketenangan. Kami memberikan standar pelayanan tertinggi untuk memastikan pengalaman menginap Anda tak terlupakan.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-6 tracking-wider uppercase text-sm">Kontak Kami</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-3">
                            <x-icon name="edit" class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" />
                            <span>Jl. Pasir Kaliki No.123, Bandung, Jawa Barat, Indonesia</span>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-6 tracking-wider uppercase text-sm">Ikuti Kami</h4>
                    <div class="flex gap-4">
                        @foreach(['Ig', 'Fb', 'Tw'] as $social)
                            <div class="w-10 h-10 rounded-full border border-slate-700 flex items-center justify-center hover:bg-primary hover:border-primary text-white transition-colors cursor-pointer">{{ $social }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-800 text-center text-sm flex flex-col md:flex-row justify-between items-center gap-4">
                <p>© {{ date('Y') }} Tibra Sare Hotel & Resort. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    {{-- Install PWA Banner --}}
    <div x-show="showInstallBanner" x-transition x-cloak class="fixed bottom-4 left-4 right-4 md:left-auto md:right-4 md:w-96 bg-white rounded-xl shadow-2xl border border-slate-200 p-4 z-[100] flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <img src="/images/IconTS.png" alt="Icon" class="w-12 h-12 rounded-lg bg-slate-100" />
            <div>
                <p class="text-sm font-bold text-slate-900">Install Tibra Sare</p>
                <p class="text-xs text-slate-500">Pesan kamar lebih cepat!</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showInstallBanner = false" class="text-xs text-slate-400 hover:text-slate-600 px-2 py-1">Nanti</button>
            <button @click="installPwa()" class="text-xs bg-primary text-white font-medium px-3 py-1.5 rounded-lg hover:bg-primary-800 transition-colors">Install</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('pwaSetup', () => ({
            showInstallBanner: false,
            deferredPrompt: null,
            init() {
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;
                    this.showInstallBanner = true;
                });
            },
            installPwa() {
                this.showInstallBanner = false;
                if (this.deferredPrompt) {
                    this.deferredPrompt.prompt();
                    this.deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            console.log('User accepted the A2HS prompt');
                        } else {
                            console.log('User dismissed the A2HS prompt');
                        }
                        this.deferredPrompt = null;
                    });
                }
            }
        }));
    });

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js').then(registration => {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, err => {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
</script>
</body>
</html>
