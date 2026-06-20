<x-layouts.app>
    <x-slot name="title">{{ $roomCategory->name }}</x-slot>
    <x-slot name="backUrl">{{ route('guest.rooms.index') }}</x-slot>

    {{-- Back Button & Header Area (Mobile First) --}}
    <div x-data="{ activeSlide: 0, slides: {{ isset($images) && $images->isNotEmpty() ? $images->count() : ($roomCategory->image_path ? 1 : 0) }} }" class="relative w-full h-[350px] sm:h-[450px] bg-slate-200">
        {{-- Hero Image / Carousel --}}
        @if(isset($images) && $images->isNotEmpty())
            <div class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar w-full h-full" @scroll.passive="activeSlide = Math.round($el.scrollLeft / $el.clientWidth)">
                @foreach($images as $image)
                    <div class="w-full h-full shrink-0 snap-center relative">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image->image_path) }}" class="w-full h-full object-cover" alt="{{ $roomCategory->name }}" />
                    </div>
                @endforeach
            </div>
            {{-- Carousel Dots --}}
            <div class="absolute bottom-14 inset-x-0 flex justify-center gap-1.5 z-10">
                <template x-for="i in slides">
                    <div class="h-1.5 rounded-full transition-all duration-300 shadow-sm" :class="activeSlide === (i-1) ? 'w-5 bg-white' : 'w-1.5 bg-white/60'"></div>
                </template>
            </div>
        @elseif($roomCategory->image_path)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($roomCategory->image_path) }}" class="w-full h-full object-cover" alt="{{ $roomCategory->name }}" />
        @else
            <div class="w-full h-full flex items-center justify-center bg-slate-100">
                <x-icon name="image" class="w-16 h-16 text-slate-300" />
            </div>
        @endif

        {{-- Gradient Overlay for Top Area --}}
        <div class="absolute inset-x-0 top-0 h-32 bg-gradient-to-b from-slate-900/60 to-transparent"></div>
    </div>

    {{-- Main Content - Sheet Effect --}}
    <div class="relative -mt-10 bg-white rounded-t-3xl shadow-[-0_-10px_20px_-15px_rgba(0,0,0,0.1)] px-5 sm:px-8 pt-8 pb-32 min-h-screen">
        
        {{-- Drag Indicator (Visual only) --}}
        <div class="absolute top-3 left-1/2 -translate-x-1/2 w-12 h-1.5 bg-slate-200 rounded-full"></div>

        {{-- Title & Price Row --}}
        <div class="flex flex-col mb-6">
            <h1 class="font-cormorant text-3xl font-bold text-slate-900 mb-2">{{ $roomCategory->name }}</h1>
            <div class="flex items-end gap-1 text-primary-600">
                <span class="text-2xl font-extrabold">{{ format_currency($roomCategory->base_price) }}</span>
                <span class="text-slate-500 text-sm mb-1 font-medium">/ malam</span>
            </div>
        </div>

        {{-- Description --}}
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-3">Tentang Kamar Ini</h2>
            <p class="text-slate-600 leading-relaxed text-sm sm:text-base text-justify">
                {{ $roomCategory->description ?: 'Kamar mewah yang dirancang dengan perhatian terhadap setiap detail, menawarkan pemandangan alam memukau dan kenyamanan tak tertandingi untuk menjamin liburan Anda sempurna.' }}
            </p>
        </div>

        {{-- Facilities --}}
        <div class="mb-8">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Fasilitas Utama</h2>
            
            @if($facilities->isNotEmpty())
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach($facilities as $facility)
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="w-10 h-10 rounded-full bg-primary-50 text-primary-600 flex items-center justify-center shrink-0">
                                <x-icon :name="$facility->icon ?: 'check-circle'" class="w-5 h-5" />
                            </div>
                            <span class="text-sm font-semibold text-slate-700 leading-tight">{{ $facility->name }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-slate-500 text-sm">Fasilitas standar resort premium.</p>
            @endif
        {{-- Rekomendasi Kamar Lain --}}
        @if(isset($otherCategories) && $otherCategories->isNotEmpty())
            <div class="mt-12 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-900">Rekomendasi Kamar Lain</h2>
                    <a href="{{ route('guest.rooms.index') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700">Lihat Semua</a>
                </div>
                
                <div class="flex overflow-x-auto pb-6 -mx-5 px-5 sm:mx-0 sm:px-0 gap-4 snap-x hide-scrollbar">
                    @foreach($otherCategories as $other)
                        <a href="{{ route('guest.rooms.show', $other->id) }}" class="min-w-[240px] w-[240px] sm:min-w-[280px] bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden snap-start flex flex-col group hover:shadow-md transition-all">
                            <div class="h-32 relative overflow-hidden bg-slate-100">
                                @if($other->image_path)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($other->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $other->name }}" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><x-icon name="image" class="w-6 h-6 text-slate-300" /></div>
                                @endif
                                <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-full text-[10px] font-bold text-slate-900 shadow-sm">
                                    {{ format_currency($other->base_price) }}
                                </div>
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="font-cormorant text-lg font-bold text-slate-900 mb-1">{{ $other->name }}</h3>
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $other->description ?: 'Kamar mewah dengan pemandangan alam memukau.' }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
        
    </div>

    {{-- Sticky Bottom Action Bar --}}
    <div class="fixed bottom-0 inset-x-0 bg-white border-t border-slate-200 p-4 pb-safe z-[60] shadow-[0_-10px_20px_-15px_rgba(0,0,0,0.1)] lg:pl-64 lg:left-0">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="hidden sm:block">
                <p class="text-xs text-slate-500 font-medium mb-0.5">Total Harga</p>
                <p class="text-lg font-bold text-primary-600">{{ format_currency($roomCategory->base_price) }}</p>
            </div>
            <a href="{{ route('book.create', $roomCategory->id) }}" class="flex-1 sm:flex-none block bg-primary hover:bg-primary-700 text-white text-center font-bold text-lg py-3.5 px-8 rounded-2xl shadow-lg shadow-primary/30 transition-transform active:scale-95">
                Pesan Sekarang
            </a>
        </div>
    </div>
</x-layouts.app>
