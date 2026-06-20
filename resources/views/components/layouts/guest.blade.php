<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Masuk' }} - {{ config('app.name', 'Tibra Sare') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Alpine.js (CDN) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Styles -->
        @vite(['resources/css/app.css'])

        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased">
        {{-- Flash Messages via SweetAlert2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if(session('status') || session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Info',
                        text: "{{ session('status') ?? session('success') }}",
                        icon: 'success',
                        confirmButtonColor: '#0F172A',
                        timer: 3000,
                        timerProgressBar: true
                    });
                });
            </script>
        @endif

        <div class="min-h-screen bg-gradient-to-br from-primary-900 via-primary-800 to-primary flex items-center justify-center p-4">
            {{-- Subtle decorative element --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-primary-400/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-accent/5 rounded-full blur-3xl"></div>
            </div>

            <div class="relative w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
