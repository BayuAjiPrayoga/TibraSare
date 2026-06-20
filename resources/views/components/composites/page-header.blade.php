@props([
    'title',
    'description' => null,
    'breadcrumbs' => [],
])

<div {{ $attributes->merge(['class' => 'mb-6']) }}>
    {{-- Breadcrumbs --}}
    @if(count($breadcrumbs) > 0)
        <nav aria-label="Breadcrumb" class="mb-2">
            <ol class="flex items-center gap-1 text-caption text-muted-foreground">
                @foreach($breadcrumbs as $index => $crumb)
                    <li class="flex items-center gap-1">
                        @if($index > 0)
                            <x-icon name="chevron-right" class="h-3 w-3 shrink-0" />
                        @endif
                        @if(!empty($crumb['href']))
                            <a href="{{ $crumb['href'] }}" class="hover:text-primary transition-colors">
                                {{ $crumb['label'] }}
                            </a>
                        @else
                            <span class="text-slate-900 font-medium">{{ $crumb['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    {{-- Title + Action --}}
    <div class="flex items-start justify-between gap-4">
        <div>
            <h1 class="text-h1 text-slate-900">{{ $title }}</h1>
            @if($description)
                <p class="text-body text-muted-foreground mt-1">{{ $description }}</p>
            @endif
        </div>
        @if(isset($action))
            <div class="shrink-0">{{ $action }}</div>
        @endif
    </div>
</div>
