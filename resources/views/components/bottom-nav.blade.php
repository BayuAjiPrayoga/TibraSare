@php
$items = config('navigation.bottom_nav', []);
$currentPath = '/' . ltrim(request()->path(), '/');
$userRole = auth()->user()?->role?->value ?? auth()->user()?->role ?? null;

$isActive = function($href) use ($currentPath) {
    if ($href === '#more') return false;
    if ($href === '/dashboard') return $currentPath === '/dashboard';
    return str_starts_with($currentPath, $href);
};
@endphp

<nav
    class="fixed bottom-0 inset-x-0 z-fixed lg:hidden bg-white border-t border-slate-200 pb-safe"
    role="navigation"
    aria-label="Navigasi utama"
>
    <div class="flex items-center justify-around h-16">
        @foreach($items as $item)
            @if(($item['admin_only'] ?? false) && $userRole !== 'admin')
                @continue
            @endif

            @php $active = $isActive($item['href']); @endphp

            <a
                href="{{ $item['href'] === '#more' ? '#' : $item['href'] }}"
                class="flex flex-col items-center justify-center gap-0.5 py-1 px-3 min-w-[64px] transition-colors duration-150 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-inset rounded-lg {{ $active ? 'text-primary' : 'text-slate-400 hover:text-slate-600' }}"
            >
                <x-icon :name="$item['icon']" class="h-5 w-5 {{ $active ? 'text-primary' : '' }}" :stroke-width="$active ? '2' : '1.75'" />
                <span class="text-[10px] leading-tight {{ $active ? 'font-semibold' : 'font-medium' }}">
                    {{ $item['label'] }}
                </span>

                @if($active)
                    <span class="absolute top-1 w-1 h-1 rounded-full bg-primary"></span>
                @endif
            </a>
        @endforeach
    </div>
</nav>
