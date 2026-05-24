<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    brand: {
                        teal: '#0d9488',
                        navy: '#0f172a',
                    }
                }
            }
        }
    }
</script>
<script>
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }
</script>
<style type="text/tailwindcss">
    @layer base {
        body {
            @apply antialiased text-slate-800 bg-slate-50 dark:bg-slate-900 dark:text-slate-200 transition-colors duration-200;
            font-family: 'Outfit', ui-sans-serif, system-ui, sans-serif;
        }
    }

    @layer components {
        .glass {
            @apply bg-white/80 backdrop-blur-xl border-b border-slate-200/80 shadow-sm dark:bg-slate-900/80 dark:border-slate-800 transition-colors duration-200;
        }
        .dashboard-shell {
            @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
        }
        .dashboard-hero {
            @apply relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:bg-slate-800/80 dark:border-slate-700 transition-colors duration-200;
        }
        .stat-card {
            @apply relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm transition-all duration-300 hover:border-slate-300 hover:shadow-md dark:bg-slate-800/80 dark:border-slate-700 dark:hover:border-slate-600;
        }
        .stat-card-icon {
            @apply flex h-12 w-12 items-center justify-center rounded-xl;
        }
        .workspace-panel {
            @apply rounded-2xl border border-slate-200/80 bg-white shadow-sm dark:bg-slate-800/80 dark:border-slate-700 transition-colors duration-200;
        }
        .note-card {
            @apply flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition-all duration-300 hover:border-indigo-200 hover:shadow-lg dark:bg-slate-800/80 dark:border-slate-700 dark:hover:border-indigo-500/50;
        }
        .btn-primary {
            @apply inline-flex items-center justify-center gap-2 rounded-xl bg-brand-teal px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 active:scale-[0.98] dark:focus:ring-offset-slate-900;
        }
        .btn-secondary {
            @apply inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 dark:focus:ring-slate-600 dark:focus:ring-offset-slate-900;
        }
        .input-field {
            @apply w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 transition focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:bg-slate-800;
        }
        .nav-pill {
            @apply rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200;
        }
        .nav-pill-active {
            @apply rounded-lg bg-teal-50 px-3 py-2 text-sm font-semibold text-brand-teal dark:bg-teal-900/40 dark:text-teal-300;
        }
        .text-gradient {
            @apply bg-gradient-to-r from-brand-navy to-brand-teal bg-clip-text text-transparent dark:from-teal-300 dark:to-indigo-300;
        }
        .badge {
            @apply inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300;
        }
        .badge-indigo {
            @apply inline-flex items-center rounded-md bg-teal-50 px-2.5 py-1 text-xs font-medium text-brand-teal dark:bg-teal-900/40 dark:text-teal-300;
        }
    }

    [x-cloak] { display: none !important; }
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
