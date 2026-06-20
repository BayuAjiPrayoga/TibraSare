@props(['user' => null])

@php
$menu = config('navigation.sidebar', []);
$currentPath = '/' . ltrim(request()->path(), '/');
$userRole = $user?->role?->value ?? $user?->role ?? null;

$isActive = function($href) use ($currentPath) {
    if ($href === '/dashboard') return $currentPath === '/dashboard';
    return str_starts_with($currentPath, $href);
};
@endphp

{{-- Desktop Sidebar (persistent) --}}
<aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:w-64 bg-white border-r border-slate-200 z-sticky">
    @include('components.partials.sidebar-content', ['menu' => $menu, 'user' => $user, 'userRole' => $userRole, 'isActive' => $isActive])
</aside>

{{-- Mobile Sidebar (Alpine.js overlay) --}}
<div
    x-show="sidebarOpen"
    x-cloak
    class="lg:hidden"
>
    {{-- Backdrop --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-modal-backdrop"
        @click="sidebarOpen = false"
    ></div>

    {{-- Slide-in Panel --}}
    <aside
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-72 bg-white z-modal flex flex-col"
    >
        {{-- Close Button --}}
        <button
            @click="sidebarOpen = false"
            class="absolute top-4 right-4 p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors cursor-pointer"
            aria-label="Tutup menu"
        >
            <x-icon name="x" class="h-5 w-5" />
        </button>

        @include('components.partials.sidebar-content', ['menu' => $menu, 'user' => $user, 'userRole' => $userRole, 'isActive' => $isActive])
    </aside>
</div>
