@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-bark-300 text-start text-base font-semibold text-bark-500 bg-cream-200/50 focus:outline-none focus:text-bark-600 focus:bg-cream-300/50 focus:border-bark-400 transition duration-200 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-muted hover:text-bark-500 hover:bg-cream-100 hover:border-bark-200 focus:outline-none focus:text-bark-500 focus:bg-cream-100 focus:border-bark-200 transition duration-200 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
