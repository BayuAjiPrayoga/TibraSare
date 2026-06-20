<x-layouts.guest>
    <x-slot name="title">Lupa Password</x-slot>

    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 motion-safe:animate-scale-in">
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center mb-4 overflow-hidden">
                <img src="/images/IconTS.png" alt="Tibra Sare Logo" class="w-full h-full object-contain" />
            </div>
            <h1 class="text-h1 text-slate-900">Lupa Password?</h1>
            <p class="text-body text-muted-foreground mt-1">
                Masukkan email Anda dan kami akan mengirim link untuk reset password.
            </p>
        </div>

        @if(session('status'))
            <div class="mb-4 p-3 rounded-lg bg-success-light text-green-800 text-caption text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <x-ui.input label="Email" type="email" icon="mail" name="email" placeholder="nama@email.com" :value="old('email')" :error="$errors->first('email')" required autocomplete="email" autofocus />

            <x-ui.button type="submit" size="lg" class="w-full">Kirim Link Reset Password</x-ui.button>
        </form>

        <p class="text-center text-caption text-muted-foreground mt-6">
            <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Kembali ke halaman masuk</a>
        </p>
    </div>
</x-layouts.guest>
