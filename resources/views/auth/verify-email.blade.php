<x-layouts.guest>
    <x-slot name="title">Verifikasi Email</x-slot>

    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 motion-safe:animate-scale-in">
        <div class="text-center mb-6">
            <h1 class="text-h1 text-slate-900">Verifikasi Email</h1>
            <p class="text-body text-muted-foreground mt-1">
                Terima kasih sudah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan.
            </p>
        </div>

        @if(session('status') == 'verification-link-sent')
            <div class="mb-4 p-3 rounded-lg bg-success-light text-green-800 text-caption text-center">
                Link verifikasi baru telah dikirim ke email yang Anda gunakan saat mendaftar.
            </div>
        @endif

        <div class="flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-ui.button type="submit">Kirim Ulang Email Verifikasi</x-ui.button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</x-layouts.guest>
