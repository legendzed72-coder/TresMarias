<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-red-600 border border-transparent rounded-full font-bold text-sm text-white tracking-wide shadow-lg shadow-red-600/25 hover:-translate-y-0.5 hover:bg-red-500 hover:shadow-xl hover:shadow-red-600/30 focus:outline-none focus:ring-2 focus:ring-red-500/40 focus:ring-offset-2 active:translate-y-0 active:bg-red-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
