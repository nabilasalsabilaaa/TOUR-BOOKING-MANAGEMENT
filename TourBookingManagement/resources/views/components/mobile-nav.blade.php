@props([
    'route' => null,
    'href' => null,
    'icon' => null,
])

@php
    $url = $route ? route($route) : ($href ?? '#');

    $isActive = false;
    if ($route) {
        $isActive = request()->routeIs($route);
    } else {
        $isActive = url()->current() === $url;
    }

    $baseClasses = 'flex items-center px-3 py-3 rounded-lg text-base font-medium transition-all duration-200';
    $colorClasses = $isActive
        ? 'bg-[#4556a6] text-white'
        : 'text-gray-700 hover:bg-[#4556a6] hover:text-white';
@endphp

<a href="{{ $url }}" {{ $attributes->merge(['class' => $baseClasses.' '.$colorClasses]) }}>
    @if($icon)
        <i class="fas fa-{{ $icon }} mr-3"></i>
    @endif
    <span>{{ $slot }}</span>
</a>
