<x-layouts.guest>
    <x-slot name="title">Masuk</x-slot>

    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 motion-safe:animate-scale-in">
        {{-- Logo & Header --}}
        <div class="text-center mb-6">
            <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center mb-4 overflow-hidden">
                <img src="/images/IconTS.png" alt="Tibra Sare Logo" class="w-full h-full object-contain" />
            </div>
            <h1 class="text-h1 text-slate-900">Masuk ke Akun Anda</h1>
            <p class="text-body text-muted-foreground mt-1">
                Tibra Sare Hotel Management
            </p>
        </div>

        {{-- Status Message --}}
        @if(session('status'))
            <div class="mb-4 p-3 rounded-lg bg-success-light text-green-800 text-caption text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <x-ui.input
                label="Email"
                type="email"
                icon="mail"
                name="email"
                placeholder="nama@email.com"
                :value="old('email')"
                :error="$errors->first('email')"
                required
                autocomplete="email"
            />

            <x-ui.input
                label="Password"
                type="password"
                icon="lock"
                name="password"
                placeholder="Masukkan password"
                :error="$errors->first('password')"
                required
                autocomplete="current-password"
            />

            {{-- Remember + Forgot --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"
                        {{ old('remember') ? 'checked' : '' }}
                    />
                    <span class="text-caption text-slate-600">Ingat saya</span>
                </label>

                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-caption text-primary hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>

            {{-- Submit --}}
            <x-ui.button type="submit" size="lg" class="w-full">
                Masuk
            </x-ui.button>
        </form>

        {{-- Divider --}}
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="bg-white px-3 text-caption text-muted-foreground">atau</span>
            </div>
        </div>

        {{-- Google Login --}}
        <a
            href="{{ route('google.redirect') }}"
            class="flex items-center justify-center gap-3 w-full h-12 rounded-lg border border-slate-300 bg-white text-body font-medium text-slate-700 hover:bg-slate-50 active:bg-slate-100 transition-colors cursor-pointer"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" />
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>
            Masuk dengan Google
        </a>

        {{-- Register Link --}}
        <p class="text-center text-caption text-muted-foreground mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">
                Daftar sekarang
            </a>
        </p>
    </div>
</x-layouts.guest>
