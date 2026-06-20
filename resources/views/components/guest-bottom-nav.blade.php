@php
$currentPath = '/' . ltrim(request()->path(), '/');

$isActive = function($href) use ($currentPath) {
    if ($href === '/dashboard') return $currentPath === '/dashboard';
    return str_starts_with($currentPath, $href);
};

$items = [
    ['href' => '/dashboard', 'icon' => 'home', 'label' => 'Beranda'],
    ['href' => '/guest-rooms', 'icon' => 'layout-grid', 'label' => 'Kamar'],
    ['href' => '/profile', 'icon' => 'user', 'label' => 'Akun'],
];
@endphp

<nav
    class="fixed bottom-0 inset-x-0 z-[100] lg:hidden bg-white border-t border-slate-200 pb-safe"
    role="navigation"
    aria-label="Navigasi Tamu"
>
    <div class="flex items-center justify-around h-16">
        @foreach($items as $item)
            @php $active = $isActive($item['href']); @endphp

            <a
                href="{{ $item['href'] }}"
                class="flex flex-col items-center justify-center gap-1 py-1 px-3 min-w-[64px] transition-colors duration-150 cursor-pointer focus-visible:outline-none rounded-lg {{ $active ? 'text-primary-600' : 'text-slate-400 hover:text-slate-600' }}"
            >
                <x-icon :name="$item['icon']" class="h-6 w-6 {{ $active ? 'text-primary-600' : '' }}" :stroke-width="$active ? '2' : '1.75'" />
                <span class="text-[10px] leading-tight {{ $active ? 'font-semibold' : 'font-medium' }}">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach
    </div>
</nav>
