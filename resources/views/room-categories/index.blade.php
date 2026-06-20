<x-layouts.app>
    <x-slot name="title">Kategori Kamar</x-slot>

    <div x-data="{
        showModal: false, editMode: false,
        form: { id: '', name: '', description: '', base_price: '' },
        resetForm() { this.form = { id: '', name: '', description: '', base_price: '' }; this.editMode = false; },
        openCreate() { this.resetForm(); this.showModal = true; },
        openEdit(cat) { this.form = { ...cat }; this.editMode = true; this.showModal = true; }
    }">
        <x-composites.page-header title="Kategori Kamar" description="Kelola tipe kamar yang tersedia.">
            <x-slot name="action">
                <x-ui.button icon="plus" @click="openCreate()">Tambah Kategori</x-ui.button>
            </x-slot>
        </x-composites.page-header>

        <div class="mb-6">
            <form method="GET" action="{{ route('room-categories.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="w-full h-10 rounded-md border border-slate-300 bg-white px-3 text-body text-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" />
                <x-ui.button type="submit" variant="secondary">Cari</x-ui.button>
            </form>
        </div>

        @if(count($categories) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @foreach($categories as $cat)
                    <div class="card p-4 hover:shadow-md transition-shadow cursor-pointer" @click="openEdit({{ json_encode($cat) }})">
                        @if($cat['image_path'])
                            <img src="{{ $cat['image_path'] }}" alt="{{ $cat['name'] }}" class="w-full h-32 object-cover rounded-lg mb-3" />
                        @else
                            <div class="w-full h-32 bg-primary-50 rounded-lg mb-3 flex items-center justify-center">
                                <x-icon name="bed-double" class="h-10 w-10 text-primary-300" />
                            </div>
                        @endif
                        <h3 class="text-h3 text-slate-900">{{ $cat['name'] }}</h3>
                        <p class="text-caption text-muted-foreground mt-1 line-clamp-2">{{ $cat['description'] ?? 'Tanpa deskripsi' }}</p>
                        <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                            <span class="text-body font-semibold text-slate-900 tabular-nums">{{ format_currency($cat['base_price']) }}</span>
                            <x-ui.badge variant="secondary" size="sm">{{ $cat['total_rooms'] }} kamar</x-ui.badge>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        @else
            <x-composites.empty-state icon="layout-grid" title="Belum ada kategori" description="Buat kategori pertama untuk mengelompokkan kamar." />
        @endif

        {{-- Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4">
            <div x-show="showModal" x-transition class="fixed inset-0 bg-black/50" @click="showModal = false"></div>
            <div x-show="showModal" x-transition class="relative w-full max-w-lg bg-white rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                    <h2 class="text-h3 text-slate-900" x-text="editMode ? 'Edit Kategori' : 'Tambah Kategori'"></h2>
                    <button @click="showModal = false" class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"><x-icon name="x" class="h-5 w-5" /></button>
                </div>
                <form :action="editMode ? '{{ url('room-categories') }}/' + form.id : '{{ route('room-categories.store') }}'" method="POST" enctype="multipart/form-data" class="px-5 py-4 space-y-4">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT" /></template>
                    <x-ui.input label="Nama Kategori" name="name" x-model="form.name" :error="$errors->first('name')" required placeholder="Contoh: Deluxe" />
                    <div class="flex flex-col gap-1.5">
                        <label class="text-caption font-medium text-slate-700">Deskripsi</label>
                        <textarea name="description" x-model="form.description" rows="3" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-body text-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" placeholder="Deskripsi kategori..."></textarea>
                    </div>
                    <x-ui.input type="number" label="Harga Dasar" name="base_price" x-model="form.base_price" :error="$errors->first('base_price')" required placeholder="500000" />
                    <div class="flex flex-col gap-1.5">
                        <label class="text-caption font-medium text-slate-700">Gambar</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-body text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-caption file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" />
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-200">
                        <x-ui.button variant="outline" type="button" @click="showModal = false">Batal</x-ui.button>
                        <x-ui.button type="submit" x-text="editMode ? 'Simpan' : 'Tambah'"></x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
