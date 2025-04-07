@props(['active'])

@php
$classes = 'block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out';

$classes = ($active ?? false)
            ? $classes . ' font-medium text-indigo-500'
            : $classes;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
