<section class="max-w-xl">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Ubah Password</h2>
        <p class="mt-1 text-sm text-gray-600">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <x-ui.input label="Password Saat Ini" type="password" name="current_password" :error="$errors->updatePassword->first('current_password')" autocomplete="current-password" />
        <x-ui.input label="Password Baru" type="password" name="password" :error="$errors->updatePassword->first('password')" autocomplete="new-password" />
        <x-ui.input label="Konfirmasi Password" type="password" name="password_confirmation" :error="$errors->updatePassword->first('password_confirmation')" autocomplete="new-password" />

        <div class="flex items-center gap-4">
            <x-ui.button type="submit">Simpan</x-ui.button>

            @if(session('status') === 'password-updated')
                <p class="text-sm text-gray-600" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
