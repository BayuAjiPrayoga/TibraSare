<x-layouts.guest>
    <x-slot name="title">Reset Password</x-slot>

    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 motion-safe:animate-scale-in">
        <div class="text-center mb-6">
            <h1 class="text-h1 text-slate-900">Reset Password</h1>
            <p class="text-body text-muted-foreground mt-1">Masukkan password baru Anda.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <x-ui.input label="Email" type="email" icon="mail" name="email" :value="old('email', $email)" :error="$errors->first('email')" required autocomplete="username" />
            <x-ui.input label="Password Baru" type="password" icon="lock" name="password" placeholder="Masukkan password baru" :error="$errors->first('password')" required autocomplete="new-password" autofocus />
            <x-ui.input label="Konfirmasi Password" type="password" icon="lock" name="password_confirmation" placeholder="Ulangi password baru" :error="$errors->first('password_confirmation')" required autocomplete="new-password" />

            <x-ui.button type="submit" size="lg" class="w-full">Reset Password</x-ui.button>
        </form>
    </div>
</x-layouts.guest>
