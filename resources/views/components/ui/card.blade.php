@props(['title' => null, 'subtitle' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'card shadow-sm border-0 ui-card']) }}>
    @if($title || $subtitle)
        <div class="card-header bg-white border-0 pb-0">
            @if($title)<h5 class="mb-1 fw-semibold">{{ $title }}</h5>@endif
            @if($subtitle)<p class="text-muted small mb-0">{{ $subtitle }}</p>@endif
        </div>
    @endif
    <div class="card-body">
        {{ $slot }}
    </div>
    @if($footer)
        <div class="card-footer bg-white border-0">
            {{ $footer }}
        </div>
    @endif
</div>
