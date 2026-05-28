<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-brand-teal hover:bg-teal-700 active:scale-[0.98] border border-transparent rounded-xl font-semibold text-sm text-white shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 dark:focus:ring-offset-slate-900 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
