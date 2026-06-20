<x-layouts.app>
    <x-slot name="title">Pengguna</x-slot>

    <div x-data="{
        showModal: false, editMode: false,
        form: { id: '', name: '', email: '', password: '', role: 'receptionist' },
        resetForm() { this.form = { id: '', name: '', email: '', password: '', role: 'receptionist' }; this.editMode = false; },
        openCreate() { this.resetForm(); this.showModal = true; },
        openEdit(u) { this.form = { ...u, password: '' }; this.editMode = true; this.showModal = true; }
    }">
        <x-composites.page-header title="Manajemen Pengguna" description="Kelola akun pengguna sistem.">
            <x-slot name="action">
                <x-ui.button icon="user-plus" @click="openCreate()">Tambah Pengguna</x-ui.button>
            </x-slot>
        </x-composites.page-header>

        <div class="mb-6">
            <form method="GET" action="{{ route('users.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full h-10 rounded-md border border-slate-300 bg-white px-3 text-body text-slate-900 focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" />
                <x-ui.button type="submit" variant="secondary">Cari</x-ui.button>
            </form>
        </div>

        @if(count($users) > 0)
            <div class="card overflow-hidden mb-6">
                <div class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$user['name']" size="md" />
                                <div>
                                    <p class="text-body font-medium text-slate-900">{{ $user['name'] }}</p>
                                    <p class="text-caption text-muted-foreground">{{ $user['email'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <x-ui.badge :variant="($user['role'] ?? 'receptionist') === 'admin' ? 'primary' : 'secondary'" size="sm">
                                    {{ ucfirst($user['role'] ?? 'receptionist') }}
                                </x-ui.badge>
                                <div class="flex items-center gap-1">
                                    <button @click="openEdit({{ json_encode($user) }})" class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-md transition-colors cursor-pointer">
                                        <x-icon name="edit" class="w-4 h-4" />
                                    </button>
                                    <form method="POST" action="{{ route('users.destroy', $user['id']) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors cursor-pointer">
                                            <x-icon name="trash" class="w-4 h-4" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @else
            <x-composites.empty-state icon="users" title="Belum ada pengguna" description="Tambah pengguna pertama." />
        @endif

        {{-- Modal --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4">
            <div x-show="showModal" x-transition class="fixed inset-0 bg-black/50" @click="showModal = false"></div>
            <div x-show="showModal" x-transition class="relative w-full max-w-md bg-white rounded-xl shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-200">
                    <h2 class="text-h3 text-slate-900" x-text="editMode ? 'Edit Pengguna' : 'Tambah Pengguna'"></h2>
                    <button @click="showModal = false" class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"><x-icon name="x" class="h-5 w-5" /></button>
                </div>
                <form :action="editMode ? '{{ url('users') }}/' + form.id : '{{ route('users.store') }}'" method="POST" class="px-5 py-4 space-y-4">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT" /></template>
                    <x-ui.input label="Nama" name="name" x-model="form.name" :error="$errors->first('name')" required />
                    <x-ui.input label="Email" type="email" name="email" x-model="form.email" :error="$errors->first('email')" required />
                    <x-ui.input label="Password" type="password" name="password" x-model="form.password" :error="$errors->first('password')" :required="false" :helper="'Kosongkan jika tidak ingin mengubah password.'" />
                    <div class="flex flex-col gap-1.5">
                        <label class="text-caption font-medium text-slate-700">Role <span class="text-destructive ml-0.5">*</span></label>
                        <select name="role" x-model="form.role" required class="w-full h-10 rounded-md border border-slate-300 bg-white pl-3 pr-10 text-body text-slate-900 appearance-none cursor-pointer focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <option value="admin">Admin</option>
                            <option value="receptionist">Receptionist</option>
                        </select>
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
