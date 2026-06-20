<section class="max-w-xl space-y-6" x-data="{ confirmDelete: false }">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Hapus Akun</h2>
        <p class="mt-1 text-sm text-gray-600">
            Setelah akun dihapus, semua data dan resource akan dihapus secara permanen. Pastikan Anda sudah mengunduh data yang ingin disimpan.
        </p>
    </header>

    <x-ui.button variant="destructive" @click="confirmDelete = true">
        Hapus Akun
    </x-ui.button>

    {{-- Delete Confirmation Modal --}}
    <div
        x-show="confirmDelete"
        x-cloak
        class="fixed inset-0 z-modal-backdrop flex items-center justify-center p-4"
    >
        <div
            x-show="confirmDelete"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50"
            @click="confirmDelete = false"
        ></div>

        <div
            x-show="confirmDelete"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-md bg-white rounded-xl shadow-xl"
        >
            <form method="POST" action="{{ route('profile.destroy') }}" class="p-6">
                @csrf
                @method('DELETE')

                <h2 class="text-lg font-medium text-gray-900">Apakah Anda yakin ingin menghapus akun?</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Setelah dihapus, semua data akan hilang secara permanen. Masukkan password Anda untuk konfirmasi.
                </p>

                <div class="mt-6">
                    <x-ui.input type="password" name="password" placeholder="Password" :error="$errors->userDeletion->first('password')" autofocus />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-ui.button variant="outline" type="button" @click="confirmDelete = false">Batal</x-ui.button>
                    <x-ui.button variant="destructive" type="submit">Hapus Akun</x-ui.button>
                </div>
            </form>
        </div>
    </div>
</section>
