<x-layouts.app>
    <x-slot name="title">Tamu</x-slot>

    <div x-data="{
        search: '',
        showModal: false, editMode: false,
        form: { id: '', full_name: '', email: '', phone: '', id_number: '', address: '' },
        resetForm() { this.form = { id: '', full_name: '', email: '', phone: '', id_number: '', address: '' }; this.editMode = false; },
        openCreate() { this.resetForm(); this.showModal = true; },
        openEdit(g) { this.form = { ...g }; this.editMode = true; this.showModal = true; }
    }">
        <x-composites.page-header title="Manajemen Tamu" description="Kelola data tamu hotel.">
            <x-slot name="action">
                <x-ui.button icon="user-plus" @click="openCreate()">Tambah Tamu</x-ui.button>
            </x-slot>
        </x-composites.page-header>

        <div class="mb-6">
            <form method="GET" action="{{ route('guests.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau telepon..." class="w-full h-10 rounded-md border border-slate-300 bg-white px-3 text-body text-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" />
                <x-ui.button type="submit" variant="secondary">Cari</x-ui.button>
            </form>
        </div>

        @if(count($guests) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                @foreach($guests as $guest)
                    <div>
                        <x-composites.guest-card :guest="$guest">
                            <x-slot name="actions">
                                <button @click.stop="openEdit({{ json_encode($guest) }})" class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-md transition-colors"><x-icon name="edit" class="w-4 h-4" /></button>
                                <form method="POST" action="{{ route('guests.destroy', $guest['id']) }}" onsubmit="return confirm('Hapus tamu ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"><x-icon name="trash" class="w-4 h-4" /></button>
                                </form>
                            </x-slot>
                        </x-composites.guest-card>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $guests->links() }}
            </div>
        @else
            <x-composites.empty-state icon="users" title="Belum ada tamu" description="Data tamu akan muncul di sini." />
        @endif

        {{-- Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4">
            <div x-show="showModal" x-transition class="fixed inset-0 bg-black/50" @click="showModal = false"></div>
            <div x-show="showModal" x-transition class="relative w-full max-w-lg bg-white rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                    <h2 class="text-h3 text-slate-900" x-text="editMode ? 'Edit Tamu' : 'Tambah Tamu'"></h2>
                    <button @click="showModal = false" class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"><x-icon name="x" class="h-5 w-5" /></button>
                </div>
                <form :action="editMode ? '{{ url('guests') }}/' + form.id : '{{ route('guests.store') }}'" method="POST" class="px-5 py-4 space-y-4">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT" /></template>
                    <x-ui.input label="Nama Lengkap" name="full_name" x-model="form.full_name" :error="$errors->first('full_name')" required />
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-ui.input label="Email" type="email" name="email" x-model="form.email" :error="$errors->first('email')" required />
                        <x-ui.input label="Telepon" name="phone" x-model="form.phone" :error="$errors->first('phone')" required />
                    </div>
                    <x-ui.input label="No. Identitas (KTP/Paspor)" name="id_number" x-model="form.id_number" :error="$errors->first('id_number')" />
                    <div class="flex flex-col gap-1.5">
                        <label class="text-caption font-medium text-slate-700">Alamat</label>
                        <textarea name="address" x-model="form.address" rows="2" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-body text-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none"></textarea>
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
