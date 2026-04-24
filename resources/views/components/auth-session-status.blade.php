@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-semibold text-sm text-leaf-400 bg-leaf-400/10 border border-leaf-400/20 rounded-xl px-4 py-2.5 mb-4']) }}>
        {{ $status }}
    </div>
@endif
