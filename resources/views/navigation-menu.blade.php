<nav x-data="{ open: false }" class="glass sticky top-0 z-50">
    <div class="dashboard-shell">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center gap-8">
                <x-site-logo href="{{ route('dashboard') }}" size="sm" />

                <div class="hidden items-center gap-1 sm:flex">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-pill-active' : 'nav-pill' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('notes') }}" class="{{ request()->routeIs('notes') ? 'nav-pill-active' : 'nav-pill' }}">
                        Notes
                    </a>
                    <a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'nav-pill-active' : 'nav-pill' }}">
                        Categories
                    </a>
                    <a href="{{ route('reminders') }}" class="{{ request()->routeIs('reminders') ? 'nav-pill-active' : 'nav-pill' }}">
                        Reminders
                    </a>
                    <a href="{{ route('history') }}" class="{{ request()->routeIs('history') ? 'nav-pill-active' : 'nav-pill' }}">
                        History
                    </a>
                    <a href="{{ route('trash') }}" class="{{ request()->routeIs('trash') ? 'nav-pill-active' : 'nav-pill' }}">
                        Trash
                    </a>
                    @if(auth()->user()?->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'nav-pill-active' : 'nav-pill' }}">
                            Admin
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-4 sm:flex">
                <!-- Dark Mode Toggle -->
                <button 
                    x-data="{ isDark: document.documentElement.classList.contains('dark') }" 
                    @click="
                        isDark = !isDark; 
                        document.documentElement.classList.toggle('dark', isDark); 
                        localStorage.theme = isDark ? 'dark' : 'light';
                    " 
                    class="rounded-full p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 transition-colors"
                    title="Toggle Dark Mode"
                >
                    <svg x-show="!isDark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                    <svg x-show="isDark" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </button>

                <div class="text-right">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 capitalize">{{ Auth::user()->user_role ?? 'user' }}</p>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-100 text-sm font-semibold text-brand-teal ring-2 ring-white transition hover:bg-teal-200">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>
                        <div class="border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <button @click="open = ! open" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 sm:hidden">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-100 dark:border-slate-800 sm:hidden bg-white dark:bg-slate-900">
        <div class="dashboard-shell space-y-1 py-3">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-pill-active block' : 'nav-pill block' }}">Dashboard</a>
            <a href="{{ route('notes') }}" class="{{ request()->routeIs('notes') ? 'nav-pill-active block' : 'nav-pill block' }}">Notes</a>
            <a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'nav-pill-active block' : 'nav-pill block' }}">Categories</a>
            <a href="{{ route('reminders') }}" class="{{ request()->routeIs('reminders') ? 'nav-pill-active block' : 'nav-pill block' }}">Reminders</a>
            <a href="{{ route('history') }}" class="{{ request()->routeIs('history') ? 'nav-pill-active block' : 'nav-pill block' }}">History</a>
            <a href="{{ route('trash') }}" class="{{ request()->routeIs('trash') ? 'nav-pill-active block' : 'nav-pill block' }}">Trash</a>
            @if(auth()->user()?->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'nav-pill-active block' : 'nav-pill block' }}">Admin</a>
            @endif
        </div>
        
        <!-- Mobile User Info & Actions -->
        <div class="border-t border-slate-200 dark:border-slate-800 pb-3 pt-4 bg-slate-50 dark:bg-slate-900/50">
            <div class="dashboard-shell flex items-center px-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-teal-100 text-sm font-semibold text-brand-teal ring-2 ring-white dark:ring-slate-700">
                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                </div>
                <div class="ml-3">
                    <div class="text-base font-semibold text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="dashboard-shell mt-3 space-y-1">
                <a href="{{ route('profile.show') }}" class="nav-pill block">
                    Profile Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf
                    <a href="{{ route('logout') }}" class="nav-pill block text-red-600 hover:text-red-700" @click.prevent="$root.submit();">
                        Log Out
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
