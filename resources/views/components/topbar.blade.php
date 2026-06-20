@props([
    'title' => null,
    'user' => null,
    'hideMenu' => false,
    'backUrl' => null,
])

<header class="sticky top-0 z-sticky bg-white/95 backdrop-blur-sm border-b border-slate-200">
    <div class="w-full max-w-7xl mx-auto flex items-center justify-between h-14 px-4 sm:px-6 lg:px-8">
        {{-- Left: Menu button (mobile) + Title --}}
        <div class="flex items-center gap-3">
            @if($backUrl)
                <a href="{{ $backUrl }}" class="p-2 -ml-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Kembali">
                    <x-icon name="arrow-left" class="h-5 w-5" />
                </a>
            @elseif(!$hideMenu)
                <button
                    type="button"
                    @click="sidebarOpen = true"
                    class="lg:hidden p-2 -ml-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                    aria-label="Buka menu"
                >
                    <x-icon name="menu" class="h-5 w-5" />
                </button>
            @endif

            <div class="flex items-center gap-6">
                @if($title)
                    <h1 class="text-h3 text-slate-900 truncate">{{ $title }}</h1>
                @endif

                {{-- Desktop Navigation Links for Guests --}}
                @if($hideMenu && !$backUrl)
                    <nav class="hidden lg:flex items-center gap-6 ml-4 border-l border-slate-200 pl-6">
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('dashboard') ? 'text-primary-700' : 'text-slate-500 hover:text-slate-900' }}">Beranda</a>
                        <a href="{{ route('guest.rooms.index') }}" class="text-sm font-semibold transition-colors {{ request()->routeIs('guest.rooms.index') ? 'text-primary-700' : 'text-slate-500 hover:text-slate-900' }}">Daftar Kamar</a>
                    </nav>
                @endif
            </div>
        </div>

        {{-- Right: Notifications + Avatar --}}
        <div class="flex items-center gap-2">
            {{-- Notification Bell --}}
            <x-dropdown align="right" width="64" content-classes="py-2 bg-white w-64 max-h-96 overflow-y-auto">
                <x-slot name="trigger">
                    <button type="button" class="relative p-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" aria-label="Notifikasi">
                        <x-icon name="bell" class="h-5 w-5" />
                    </button>
                </x-slot>

                <div class="px-4 py-2 border-b border-slate-100 text-sm font-semibold text-slate-900">
                    Notifikasi
                </div>
                <div class="p-4 text-center text-sm text-slate-500">
                    Belum ada notifikasi baru.
                </div>
            </x-dropdown>

            {{-- User Avatar --}}
            @if($user)
                <x-dropdown align="right" width="48" content-classes="py-1 bg-white border border-slate-200">
                    <x-slot name="trigger">
                        <button class="cursor-pointer focus:outline-none">
                            <x-ui.avatar :name="$user->name" :src="$user->avatar ?? null" size="sm" />
                        </button>
                    </x-slot>

                    <x-dropdown-link href="/">Halaman Depan Publik</x-dropdown-link>
                    <x-dropdown-link href="{{ route('profile.edit') }}">Pengaturan Akun</x-dropdown-link>
                    <div class="border-t border-slate-100 my-1"></div>
                    <x-dropdown-link href="{{ route('logout') }}" method="POST" class="text-destructive">Keluar</x-dropdown-link>
                </x-dropdown>
            @endif
        </div>
    </div>
</header>
