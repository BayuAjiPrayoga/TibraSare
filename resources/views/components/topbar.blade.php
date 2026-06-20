@props([
    'title' => null,
    'user' => null,
    'hideMenu' => false,
])

<header class="sticky top-0 z-sticky bg-white/95 backdrop-blur-sm border-b border-slate-200">
    <div class="flex items-center justify-between h-14 px-4 sm:px-6 lg:px-8">
        {{-- Left: Menu button (mobile) + Title --}}
        <div class="flex items-center gap-3">
            @unless($hideMenu)
                <button
                    type="button"
                    @click="sidebarOpen = true"
                    class="lg:hidden p-2 -ml-2 rounded-md text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                    aria-label="Buka menu"
                >
                    <x-icon name="menu" class="h-5 w-5" />
                </button>
            @endunless

            @if($title)
                <h1 class="text-h3 text-slate-900 truncate">{{ $title }}</h1>
            @endif
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

                    <x-dropdown-link href="{{ route('profile.edit') }}">Profil</x-dropdown-link>
                    <x-dropdown-link href="{{ route('logout') }}" method="POST">Keluar</x-dropdown-link>
                </x-dropdown>
            @endif
        </div>
    </div>
</header>
