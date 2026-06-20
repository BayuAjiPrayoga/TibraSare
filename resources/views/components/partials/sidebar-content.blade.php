{{-- Sidebar inner content (shared between desktop and mobile) --}}

{{-- Logo --}}
<div class="flex items-center gap-3 px-5 h-16 border-b border-slate-200 shrink-0">
    <div class="w-8 h-8 rounded-lg overflow-hidden flex items-center justify-center">
        <img src="/images/IconTS.png" alt="Tibra Sare Logo" class="w-full h-full object-contain" />
    </div>
    <div>
        <p class="text-h3 text-slate-900 leading-tight">Tibra Sare</p>
        <p class="text-[10px] text-muted-foreground leading-tight">Hotel Management</p>
    </div>
</div>

{{-- Navigation --}}
<nav class="flex-1 overflow-y-auto py-4 px-3 scrollbar-hide">
    @foreach($menu as $group)
        @php
            $groupAdminOnly = $group['admin_only'] ?? false;
            if ($groupAdminOnly && $userRole !== 'admin') continue;

            $visibleItems = collect($group['items'])->filter(function($item) use ($userRole) {
                if (($item['admin_only'] ?? false) && $userRole !== 'admin') return false;
                return true;
            });

            if ($visibleItems->isEmpty()) continue;
        @endphp

        <div class="mb-5">
            <p class="text-overline text-muted-foreground uppercase tracking-wider px-3 mb-2">
                {{ $group['group'] }}
            </p>
            <div class="space-y-0.5">
                @foreach($visibleItems as $item)
                    @php $active = $isActive($item['href']); @endphp
                    <a
                        href="{{ $item['href'] }}"
                        @if(isset($onClose)) @click="sidebarOpen = false" @endif
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-body font-medium transition-colors duration-150 cursor-pointer {{ $active ? 'bg-primary-50 text-primary-900' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}"
                    >
                        <x-icon :name="$item['icon']" class="h-5 w-5 shrink-0 {{ $active ? 'text-primary' : 'text-slate-400' }}" />
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</nav>

{{-- User Info at Bottom --}}
@if($user)
    <div class="shrink-0 border-t border-slate-200 p-3">
        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer">
            <x-ui.avatar :name="$user->name" :src="$user->avatar" size="sm" />
            <div class="flex-1 min-w-0">
                <p class="text-body font-medium text-slate-900 truncate">{{ $user->name }}</p>
                <p class="text-caption text-muted-foreground truncate">{{ $user->email }}</p>
            </div>
        </a>
    </div>
@endif
