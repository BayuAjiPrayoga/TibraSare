@props([
    'icon' => null,
    'title',
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-12 px-6 text-center']) }}>
    @if($icon)
        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
            <x-icon :name="$icon" class="h-8 w-8 text-slate-400" stroke-width="1.5" />
        </div>
    @endif

    <h3 class="text-h3 text-slate-900">{{ $title }}</h3>

    @if($description)
        <p class="text-body text-muted-foreground mt-1 max-w-sm">{{ $description }}</p>
    @endif

    @if(isset($action))
        <div class="mt-4">{{ $action }}</div>
    @endif
</div>
