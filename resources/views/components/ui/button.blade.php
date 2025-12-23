@props([
    'variant' => 'primary',
    'size' => 'md',
    'as' => 'button',
    'href' => null,
    'type' => 'button',
])
@php
    $base = 'btn shadow-sm fw-semibold';
    $variants = [
        'primary' => 'btn-primary-gradient text-white border-0',
        'secondary' => 'btn-outline-secondary border-2',
        'ghost' => 'btn-ghost',
        'success' => 'btn-success text-white border-0',
    ];
    $sizes = [
        'sm' => 'btn-sm px-3 py-2',
        'md' => 'px-4 py-2',
        'lg' => 'btn-lg px-4 py-2',
    ];
    $variantClass = isset($variants[$variant]) ? $variants[$variant] : $variants['primary'];
    $sizeClass = isset($sizes[$size]) ? $sizes[$size] : $sizes['md'];
    $classes = trim($base . ' ' . $variantClass . ' ' . $sizeClass);
@endphp

@if($as === 'a' && $href)
    <a {{ $attributes->merge(['href' => $href, 'class' => $classes, 'role' => 'button']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
