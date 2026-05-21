<x-app-layout>
    @php
        $user = auth()->user();
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
    @endphp

    <div class="min-h-screen pb-16">
        <div class="dashboard-shell space-y-8 py-8">
            {{-- Welcome banner --}}
            <section class="dashboard-hero">
                <div class="absolute inset-0 bg-gradient-to-br from-teal-50/80 via-white to-slate-50/50"></div>
                <div class="absolute -right-8 -top-8 h-40 w-40 rounded-full bg-teal-100/40 blur-2xl"></div>
                <div class="absolute -bottom-10 left-1/3 h-32 w-32 rounded-full bg-brand-navy/10 blur-2xl"></div>
                <div class="relative flex flex-col gap-6 p-8 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-5">
                        <img
                            src="{{ asset('images/logo.png') }}"
                            alt="NoteHub"
                            class="hidden h-16 w-16 shrink-0 rounded-full object-cover shadow-md ring-2 ring-white sm:block"
                        >
                    <div>
                        <p class="text-sm font-medium text-brand-teal">{{ $greeting }}</p>
                        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                            Welcome back, {{ $user->first_name }}
                        </h1>
                        <p class="mt-2 max-w-xl text-sm text-slate-600">
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

        {{-- Tailwind CSS CDN Test --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-8">
            <div class="p-6 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-[2rem] shadow-2xl transform hover:scale-[1.02] transition-transform duration-300">
                <div class="flex items-center justify-between bg-white/20 backdrop-blur-md rounded-2xl p-6 border border-white/30">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 flex items-center justify-center bg-white rounded-full shadow-lg">
                            <svg class="w-6 h-6 text-purple-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-white tracking-wide">Tailwind CDN is Active!</h3>
                            <p class="text-white/80 font-medium mt-1">If you can see these gradient colors, blur effects, and animations, it means the CDN is working perfectly without NPM.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
