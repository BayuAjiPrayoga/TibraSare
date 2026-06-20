<x-layouts.app>
    <x-slot name="title">Kamar</x-slot>

    <div x-data="{
        search: '',
        statusFilter: 'all',
        showModal: false,
        editMode: false,
        form: { id: '', room_number: '', room_category_id: '', price: '', status: 'available', facilities: [], images: [] },
        resetForm() {
            this.form = { id: '', room_number: '', room_category_id: '', price: '', status: 'available', facilities: [], images: [] };
            this.editMode = false;
        },
        openCreate() { this.resetForm(); this.showModal = true; },
        openEdit(room) {
            this.form = { ...room, room_category_id: room.category_id, facilities: room.facilities || [], images: room.images || [] };
            this.editMode = true;
            this.showModal = true;
        },
        deleteImage(imageId) {
            if (confirm('Yakin ingin menghapus foto ini?')) {
                const form = document.getElementById('delete-image-form');
                form.action = '{{ url('rooms/images') }}/' + imageId;
                form.submit();
            }
        }
    }">
        <x-composites.page-header title="Manajemen Kamar" description="Kelola seluruh kamar hotel.">
            <x-slot name="action">
                <x-ui.button icon="plus" @click="openCreate()">Tambah Kamar</x-ui.button>
            </x-slot>
        </x-composites.page-header>

        {{-- Search & Filter --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-6">
            <form method="GET" action="{{ route('rooms.index') }}" class="flex-1 flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor kamar..." class="w-full h-10 rounded-md border border-slate-300 bg-white px-3 text-body text-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" />
                <x-ui.button type="submit" variant="secondary">Cari</x-ui.button>
            </form>
            <div class="flex gap-2 overflow-x-auto pb-1">
                <button @click="statusFilter = 'all'" :class="statusFilter === 'all' ? 'bg-primary text-white' : 'bg-white text-slate-600 border border-slate-300'" class="px-3 py-2 rounded-lg text-caption font-medium whitespace-nowrap transition-colors cursor-pointer">Semua</button>
                @foreach(config('navigation.room_status') as $key => $cfg)
                    <button @click="statusFilter = '{{ $key }}'" :class="statusFilter === '{{ $key }}' ? 'bg-primary text-white' : 'bg-white text-slate-600 border border-slate-300'" class="px-3 py-2 rounded-lg text-caption font-medium whitespace-nowrap transition-colors cursor-pointer">{{ $cfg['label'] }}</button>
                @endforeach
            </div>
        </div>

        {{-- Room Grid --}}
        @if(count($rooms) > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 mb-6">
                @foreach($rooms as $room)
                    <div
                        x-show="(statusFilter === 'all' || '{{ $room['status'] }}' === statusFilter)"
                        @click="openEdit({{ json_encode($room) }})"
                        class="cursor-pointer"
                    >
                        <x-composites.room-card :room="$room" />
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $rooms->links() }}
            </div>
        @else
            <x-composites.empty-state icon="bed-double" title="Belum ada kamar" description="Tambah kamar pertama untuk memulai." />
        @endif

        {{-- Create/Edit Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4">
            <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

            <div x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg bg-white rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                    <h2 class="text-h3 text-slate-900" x-text="editMode ? 'Edit Kamar' : 'Tambah Kamar'"></h2>
                    <button @click="showModal = false" class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"><x-icon name="x" class="h-5 w-5" /></button>
                </div>

                <form :action="editMode ? '{{ url('rooms') }}/' + form.id : '{{ route('rooms.store') }}'" method="POST" enctype="multipart/form-data" class="px-5 py-4 space-y-4">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT" /></template>

                    <x-ui.input label="Nomor Kamar" name="room_number" x-model="form.room_number" :error="$errors->first('room_number')" required placeholder="Contoh: 101" />

                    <div class="flex flex-col gap-1.5">
                        <label class="text-caption font-medium text-slate-700">Kategori <span class="text-destructive ml-0.5">*</span></label>
                        <select name="room_category_id" x-model="form.room_category_id" required class="w-full h-10 rounded-md border border-slate-300 bg-white pl-3 pr-10 text-body text-slate-900 appearance-none cursor-pointer focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <option value="" disabled>Pilih kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('room_category_id') <p class="text-caption text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <x-ui.input type="number" label="Harga per Malam" name="price" x-model="form.price" :error="$errors->first('price')" required placeholder="450000" />

                    <div class="flex flex-col gap-1.5">
                        <label class="text-caption font-medium text-slate-700">Status</label>
                        <select name="status" x-model="form.status" class="w-full h-10 rounded-md border border-slate-300 bg-white pl-3 pr-10 text-body text-slate-900 appearance-none cursor-pointer focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            @foreach(config('navigation.room_status') as $key => $cfg)
                                <option value="{{ $key }}">{{ $cfg['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if(count($facilities) > 0)
                        <div class="flex flex-col gap-1.5">
                            <label class="text-caption font-medium text-slate-700">Fasilitas</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($facilities as $facility)
                                    <label class="inline-flex items-center gap-1.5 text-caption cursor-pointer">
                                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" :checked="form.facilities.includes({{ $facility->id }})" class="rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer" />
                                        {{ $facility->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col gap-1.5">
                        <label class="text-caption font-medium text-slate-700">Galeri Foto Kamar</label>
                        
                        {{-- Existing Images Preview --}}
                        <template x-if="editMode && form.images && form.images.length > 0">
                            <div class="mb-3 grid grid-cols-2 sm:grid-cols-3 gap-3">
                                <template x-for="image in form.images" :key="image.id">
                                    <div class="relative group rounded-md overflow-hidden aspect-[4/3] bg-slate-100 border border-slate-200">
                                        <img :src="image.url" class="w-full h-full object-cover" />
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <button type="button" @click.prevent="deleteImage(image.id)" class="bg-white/20 hover:bg-destructive text-white p-2 rounded-full transition-colors">
                                                <x-icon name="trash-2" class="w-4 h-4" />
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <input type="file" name="images[]" multiple accept="image/*" class="w-full text-body text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" />
                        <p class="text-[11px] text-slate-500 mt-1">Anda dapat memilih lebih dari satu foto. Format: JPG, PNG, WEBP (Maks 2MB/foto).</p>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200">
                        <x-ui.button variant="outline" type="button" @click="showModal = false">Batal</x-ui.button>
                        <x-ui.button type="submit" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Kamar'"></x-ui.button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Form (hidden, triggered by JS) --}}
        <form id="delete-room-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        {{-- Delete Image Form (hidden, triggered by JS) --}}
        <form id="delete-image-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-layouts.app>
