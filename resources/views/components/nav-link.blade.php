@props(['active'])

@php
$classes = 'inline-flex items-center px-1 pt-2.5 border-b-2 text-md font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';

$classes = ($active ?? false)
            ? $classes . ' border-[#5BB85C] text-[#5BB85C] focus:border-[#5BB85C]'
            : $classes . ' border-transparent';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
