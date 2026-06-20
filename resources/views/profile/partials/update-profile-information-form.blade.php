<section class="max-w-xl">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-gray-600">Perbarui informasi profil dan alamat email akun Anda.</p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Foto Profil</label>
            <div class="flex items-center gap-4">
                <x-ui.avatar :name="auth()->user()->name" :src="auth()->user()->avatar" size="xl" />
                <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" />
            </div>
            @error('avatar')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <x-ui.input label="Nama" name="name" :value="old('name', auth()->user()->name)" :error="$errors->first('name')" required autocomplete="name" autofocus />

        <x-ui.input label="Email" type="email" name="email" :value="old('email', auth()->user()->email)" :error="$errors->first('email')" required autocomplete="username" />

        @if($mustVerifyEmail ?? false)
            @if(auth()->user()->email_verified_at === null)
                <div>
                    <p class="mt-2 text-sm text-gray-800">
                        Alamat email Anda belum diverifikasi.
                        <form method="POST" action="{{ route('verification.send') }}" class="inline">
                            @csrf
                            <button type="submit" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </button>
                        </form>
                    </p>

                    @if(session('status') === 'verification-link-sent')
                        <div class="mt-2 text-sm font-medium text-green-600">
                            Link verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    @endif
                </div>
            @endif
        @endif

        <div class="flex items-center gap-4">
            <x-ui.button type="submit">Simpan</x-ui.button>

            @if(session('status') === 'profile-updated')
                <p class="text-sm text-gray-600" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
