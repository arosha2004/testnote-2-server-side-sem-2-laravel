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
                    @if(auth()->user()?->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'nav-pill-active' : 'nav-pill' }}">
                            Admin
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-4 sm:flex">
                <div class="text-right">
                    <p class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500 capitalize">{{ Auth::user()->user_role ?? 'user' }}</p>
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

    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-slate-100 sm:hidden">
        <div class="dashboard-shell space-y-1 py-3">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-pill-active block' : 'nav-pill block' }}">Dashboard</a>
            <a href="{{ route('notes') }}" class="{{ request()->routeIs('notes') ? 'nav-pill-active block' : 'nav-pill block' }}">Notes</a>
            <a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'nav-pill-active block' : 'nav-pill block' }}">Categories</a>
            <a href="{{ route('reminders') }}" class="{{ request()->routeIs('reminders') ? 'nav-pill-active block' : 'nav-pill block' }}">Reminders</a>
            <a href="{{ route('history') }}" class="{{ request()->routeIs('history') ? 'nav-pill-active block' : 'nav-pill block' }}">History</a>
        </div>
    </div>
</nav>
