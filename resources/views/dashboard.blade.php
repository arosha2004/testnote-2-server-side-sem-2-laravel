<x-app-layout>
    @php
        $user = auth()->user();
    @endphp

    <div class="min-h-screen pb-16">
        <div class="dashboard-shell space-y-8 py-8">
            {{-- Welcome banner --}}
            <section class="dashboard-hero">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-50/80 via-white to-slate-50/50 dark:from-slate-800 dark:via-slate-800 dark:to-slate-800/50 transition-colors duration-200"></div>
                <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-teal-100/40 dark:bg-teal-900/20 blur-2xl transition-colors duration-200"></div>
                <div class="absolute -bottom-10 left-1/3 h-32 w-32 rounded-full bg-brand-navy/10 dark:bg-brand-teal/10 blur-2xl transition-colors duration-200"></div>
                <div class="relative flex flex-col gap-6 p-8 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-5">
                        <img
                            src="{{ asset('images/logo.png') }}"
                            alt="NoteHub"
                            class="hidden h-16 w-16 shrink-0 rounded-full object-cover shadow-md ring-2 ring-white dark:ring-slate-700 sm:block transition-all duration-200"
                        >
                    <div>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl transition-colors duration-200">
                            Welcome back, {{ $user->first_name }}
                        </h1>
                        <p class="mt-2 max-w-xl text-sm text-slate-600 dark:text-slate-300 transition-colors duration-200">
                            Manage your notes, categories, and reminders from one place. Stay organized and productive.
                        </p>
                    </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('categories') }}" class="btn-secondary">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            Categories
                        </a>
                        <a href="{{ route('reminders') }}" class="btn-secondary">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Reminders
                        </a>
                    </div>
                </div>
            </section>

            {{-- Stats --}}
            <section>
                <livewire:dashboard-stats />
            </section>

            {{-- Workspace --}}
            <section class="workspace-panel p-6 sm:p-8">
                <livewire:note-manager />
            </section>
        </div>

    </div>
</x-app-layout>
