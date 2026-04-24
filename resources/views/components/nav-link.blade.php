@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-bark-300 text-sm font-semibold leading-5 text-bark-600 focus:outline-none focus:border-bark-400 transition duration-200 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-muted hover:text-bark-500 hover:border-bark-200 focus:outline-none focus:text-bark-500 focus:border-bark-200 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
