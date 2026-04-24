@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full border border-bark-200/40 bg-white/60 focus:border-bark-300 focus:ring-2 focus:ring-bark-300/20 rounded-xl px-4 py-2.5 text-bark-600 placeholder-muted/50 font-sans text-sm shadow-sm transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
