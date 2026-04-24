@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-bark-600 mb-1']) }}>
    {{ $value ?? $slot }}
</label>
