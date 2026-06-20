<x-layouts.guest>
    <x-slot name="title">Konfirmasi Password</x-slot>

    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 motion-safe:animate-scale-in">
        <div class="text-center mb-6">
            <h1 class="text-h1 text-slate-900">Konfirmasi Password</h1>
            <p class="text-body text-muted-foreground mt-1">
                Ini adalah area aman. Silakan konfirmasi password Anda sebelum melanjutkan.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf
            <x-ui.input label="Password" type="password" icon="lock" name="password" placeholder="Masukkan password" :error="$errors->first('password')" required autocomplete="current-password" autofocus />
            <x-ui.button type="submit" size="lg" class="w-full">Konfirmasi</x-ui.button>
        </form>
    </div>
</x-layouts.guest>
