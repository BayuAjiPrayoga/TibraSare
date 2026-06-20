<x-layouts.app>
    <x-slot name="title">Daftar Kamar</x-slot>

    <div class="px-5 sm:px-8 py-6 mb-20 lg:mb-6">
        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-2">Eksplorasi Kamar</h1>
            <p class="text-slate-500">Temukan pilihan kamar terbaik untuk pengalaman liburan tak terlupakan Anda.</p>
        </div>

        <div class="flex flex-col gap-6">
            @forelse($roomCategories as $category)
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition-all">
                    <div class="h-56 relative overflow-hidden bg-slate-100">
                        @if($category->image_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($category->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $category->name }}" />
                        @else
                            <div class="w-full h-full flex items-center justify-center"><x-icon name="image" class="w-10 h-10 text-slate-300" /></div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-4 py-2 rounded-full text-sm font-bold text-slate-900 shadow-sm">
                            {{ format_currency($category->base_price) }}
                        </div>
                        @if($category->available_rooms_count > 0)
                            <div class="absolute top-4 left-4 bg-success/90 backdrop-blur text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Tersedia
                            </div>
                        @else
                            <div class="absolute top-4 left-4 bg-slate-800/80 backdrop-blur text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                                Penuh
                            </div>
                        @endif
                    </div>
                    <div class="p-5 flex-1 flex flex-col">
                        <h3 class="font-cormorant text-2xl font-bold text-slate-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-sm text-slate-500 line-clamp-3 mb-6 flex-1">{{ $category->description ?: 'Kamar mewah dengan pemandangan alam memukau.' }}</p>
                        <a href="{{ route('guest.rooms.show', $category->id) }}" class="block w-full py-3 bg-primary-50 hover:bg-primary-100 text-primary-700 text-center rounded-xl font-bold transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-12 text-slate-500 bg-slate-50 rounded-3xl border border-slate-100">
                    <x-icon name="hotel" class="w-12 h-12 mx-auto text-slate-300 mb-3" />
                    Kategori kamar belum tersedia.
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.app>
