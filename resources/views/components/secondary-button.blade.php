<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-cream-100 border border-bark-200/30 rounded-full font-semibold text-sm text-bark-500 tracking-wide shadow-sm hover:bg-cream-200 hover:border-bark-200/50 focus:outline-none focus:ring-2 focus:ring-bark-300/20 focus:ring-offset-2 active:bg-cream-300 transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
