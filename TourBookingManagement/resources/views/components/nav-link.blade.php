@props([
    'route' => null,
    'href' => null,
    'icon' => null,
])

@php
    // Tentukan URL tujuan
    $url = $route ? route($route) : ($href ?? '#');

    // Deteksi apakah link ini active
    if ($route) {
        $isActive = request()->routeIs($route);
    } else {
        $isActive = url()->current() === $url;
    }

    // Tanpa background & tanpa pill besar
    $baseClasses = 'inline-flex items-center px-2 py-1 text-sm font-medium gap-2 transition-all duration-200';

    // Active: cuma warna teks + garis bawah tipis
    $colorClasses = $isActive
        ? 'text-yellow-300 border-b-2 border-yellow-300'
        : 'text-white/90 hover:text-white';
@endphp

<a href="{{ $url }}" {{ $attributes->merge(['class' => $baseClasses.' '.$colorClasses]) }}>
    @if($icon)
        <i class="fas fa-{{ $icon }} text-xs"></i>
    @endif
    <span>{{ $slot }}</span>
</a>
