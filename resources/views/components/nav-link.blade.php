@props(['href', 'active' => false])

@php
$classes = $active
    ? 'block px-4 py-2 rounded-md bg-gray-700 text-white font-semibold'
    : 'block px-4 py-2 rounded-md text-gray-300 hover:bg-gray-700 hover:text-white';
@endphp

<a href="{{ $href }}" class="{{ $classes }}">
    {{ $slot }}
</a>
