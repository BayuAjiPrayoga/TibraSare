<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Tibra Sare') }} - {{ config('app.name', 'Tibra Sare') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Alpine.js (CDN) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- PWA Configuration -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/IconTS.png') }}">
        <meta name="theme-color" content="#0F172A">

        <!-- Styles -->
        @vite(['resources/css/app.css'])

        <!-- Page-specific head -->
        @stack('head')

        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased">
        @php
            $user = auth()->user();
            $userRole = $user?->role?->value ?? $user?->role ?? null;
            $isGuest = $userRole === 'guest';
        @endphp

        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-[var(--color-background)]">
            {{-- Sidebar (hidden for guest role) --}}
            @unless($isGuest)
                <x-sidebar :user="$user" />
            @endunless

            {{-- Main Content Area --}}
            <div class="{{ !$isGuest ? 'lg:pl-64' : '' }} flex flex-col min-h-screen">
                {{-- Top Bar --}}
                <x-topbar
                    :title="$title ?? null"
                    :user="$user"
                    :hide-menu="$isGuest"
                    :back-url="isset($backUrl) ? $backUrl : null"
                />

                {{-- Page Content --}}
                <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-24 lg:pb-8">
                    {{-- Flash Messages via SweetAlert2 --}}
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    @if(session('success'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: "{{ session('success') }}",
                                    icon: 'success',
                                    confirmButtonColor: '#0F172A',
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            });
                        </script>
                    @endif

                    @if(session('error'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: "{{ session('error') }}",
                                    icon: 'error',
                                    confirmButtonColor: '#0F172A'
                                });
                            });
                        </script>
                    @endif

                    {{ $slot }}
                </main>
            </div>

            {{-- Mobile Bottom Navigation --}}
            @if($isGuest)
                @unless(request()->routeIs('guest.rooms.show') || request()->routeIs('book.create') || request()->routeIs('book.store'))
                    <x-guest-bottom-nav />
                @endunless
            @else
                <x-bottom-nav />
            @endif
        </div>

        {{-- Page-specific scripts --}}
        @stack('scripts')

        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js').then(registration => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }, err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
                });
            }
        </script>
    </body>
</html>
