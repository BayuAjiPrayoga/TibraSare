<x-layouts.app>
    <x-slot name="title">Pesan {{ $category->name }}</x-slot>

    <div class="max-w-4xl mx-auto" x-data="{
        check_in_date: '{{ old('check_in_date') }}',
        check_out_date: '{{ old('check_out_date') }}',
        base_price: {{ $category->base_price }},
        get nights() {
            if (!this.check_in_date || !this.check_out_date) return 0;
            const start = new Date(this.check_in_date);
            const end = new Date(this.check_out_date);
            const diffTime = Math.max(0, end - start);
            return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        },
        get totalPrice() {
            return this.nights > 0 ? this.nights * this.base_price : 0;
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
        }
    }">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-h2 text-slate-900">Pesan Kamar</h1>
                <p class="text-body text-slate-500 mt-1">Selesaikan pemesanan Anda untuk kamar {{ $category->name }}.</p>
            </div>
            <a href="{{ route('guest.rooms.show', $category->id) }}">
                <x-ui.button variant="ghost" size="sm" icon="arrow-left">Kembali ke Kamar</x-ui.button>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Form Section --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="card p-6">
                    <h2 class="text-h3 border-b border-slate-100 pb-3 mb-4">Detail Reservasi</h2>
                    
                    @if($availableRoomsCount === 0)
                        <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-4 text-sm font-medium">
                            Maaf, saat ini tidak ada kamar yang tersedia untuk kategori {{ $category->name }}.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('book.store', $category->id) }}" class="space-y-5" @submit="if(nights <= 0) { alert('Tanggal Check-Out harus setelah Check-In'); $event.preventDefault(); }">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-ui.input 
                                label="Tanggal Check-In" 
                                type="date" 
                                name="check_in_date" 
                                icon="calendar-days" 
                                x-model="check_in_date"
                                :error="$errors->first('check_in_date')"
                                required 
                                :disabled="$availableRoomsCount === 0"
                            />
                            <x-ui.input 
                                label="Tanggal Check-Out" 
                                type="date" 
                                name="check_out_date" 
                                icon="calendar-days" 
                                x-model="check_out_date"
                                :error="$errors->first('check_out_date')"
                                required 
                                :disabled="$availableRoomsCount === 0"
                            />
                        </div>

                        <h3 class="text-h4 mt-6 mb-2">Informasi Tamu</h3>
                        <p class="text-sm text-slate-500 mb-4">Kami memerlukan data tambahan untuk keperluan check-in.</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-caption font-medium text-slate-700">Tipe Identitas</label>
                                <select name="identity_type" {{ $availableRoomsCount === 0 ? 'disabled' : '' }} class="w-full h-10 rounded-md border border-slate-300 bg-white pl-3 pr-10 text-body text-slate-900 appearance-none cursor-pointer focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none disabled:bg-slate-50 disabled:text-slate-500 disabled:cursor-not-allowed">
                                    <option value="KTP" {{ old('identity_type') == 'KTP' ? 'selected' : '' }}>KTP</option>
                                    <option value="Passport" {{ old('identity_type') == 'Passport' ? 'selected' : '' }}>Passport</option>
                                    <option value="SIM" {{ old('identity_type') == 'SIM' ? 'selected' : '' }}>SIM</option>
                                </select>
                                @error('identity_type') <p class="text-caption text-destructive">{{ $message }}</p> @enderror
                            </div>
                            <x-ui.input 
                                label="Nomor Identitas" 
                                name="identity_number" 
                                icon="user"
                                placeholder="NIK / No Passport" 
                                :value="old('identity_number')"
                                :error="$errors->first('identity_number')"
                                required 
                                :disabled="$availableRoomsCount === 0"
                            />
                        </div>
                        <x-ui.input 
                            label="Nomor Handphone / WhatsApp" 
                            type="tel"
                            name="phone"
                            icon="phone"
                            placeholder="08123456789" 
                            :value="old('phone')"
                            :error="$errors->first('phone')"
                            required 
                            :disabled="$availableRoomsCount === 0"
                        />
                        
                        @error('room_id')
                            <p class="text-sm text-red-600 bg-red-50 p-3 rounded">{{ $message }}</p>
                        @enderror

                        <div class="pt-4 border-t border-slate-100">
                            <x-ui.button 
                                type="submit" 
                                class="w-full" 
                                size="lg" 
                                :disabled="$availableRoomsCount === 0"
                            >
                                Konfirmasi & Pesan Kamar
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary Section --}}
            <div class="lg:col-span-1">
                <div class="card sticky top-6 overflow-hidden">
                    <div class="h-40 relative">
                        @if($category->image_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($category->image_path) }}" alt="{{ $category->name }}" class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                <span class="text-slate-400">Tanpa Gambar</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4">
                            <span class="text-xs font-semibold text-white/80 uppercase tracking-wider block mb-1">Kategori</span>
                            <h3 class="text-xl font-cormorant font-bold text-white leading-none">{{ $category->name }}</h3>
                        </div>
                    </div>
                    
                    <div class="p-5 bg-slate-50 border-t border-slate-100">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Harga per Malam</span>
                                <span class="font-semibold text-slate-900">{{ format_currency($category->base_price) }}</span>
                            </div>
                            <div class="flex justify-between border-b border-slate-200 pb-2">
                                <span class="text-slate-500">Durasi Menginap</span>
                                <span class="font-semibold text-slate-900" x-text="nights + ' Malam'">0 Malam</span>
                            </div>
                            <div class="flex justify-between pt-2">
                                <span class="text-slate-600 font-medium">Total Tagihan</span>
                                <span class="font-bold text-xl text-primary-700" x-text="formatCurrency(totalPrice)">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    @if(isset($galleryImages) && count($galleryImages) > 0)
                        <div class="p-5 bg-white border-t border-slate-100">
                            <h4 class="text-caption font-bold text-slate-900 uppercase tracking-wider mb-3">Galeri Kamar</h4>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($galleryImages as $img)
                                    <div class="aspect-square rounded overflow-hidden bg-slate-100">
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($img->image_path) }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
