<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-br from-bark-300 to-bark-400 border border-transparent rounded-full font-bold text-sm text-white tracking-wide shadow-lg shadow-bark-400/25 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-bark-400/30 focus:outline-none focus:ring-2 focus:ring-bark-300/40 focus:ring-offset-2 active:translate-y-0 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
