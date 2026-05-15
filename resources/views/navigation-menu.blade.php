<nav x-data="{ open: false }" class="glass sticky top-0 z-50 border-b border-slate-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-3xl font-black text-gradient tracking-tighter">
                        NOTEHUB
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="text-sm font-bold">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('notes') }}" :active="request()->routeIs('notes')" class="text-sm font-bold">
                        {{ __('Notes') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('categories') }}" :active="request()->routeIs('categories')" class="text-sm font-bold">
                        {{ __('Categories') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('reminders') }}" :active="request()->routeIs('reminders')" class="text-sm font-bold">
                        {{ __('Reminders') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('history') }}" :active="request()->routeIs('history')" class="text-sm font-bold">
                        {{ __('History') }}
                    </x-nav-link>
                    @if(auth()->user()?->role === 'admin')
                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.*')" class="text-sm font-bold text-indigo-600">
                        🛡️ Admin
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 text-sm font-bold text-slate-700 hover:text-indigo-600 transition">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
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
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden glass mt-2 border-t border-slate-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('notes') }}" :active="request()->routeIs('notes')">
                {{ __('Notes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('categories') }}" :active="request()->routeIs('categories')">
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('reminders') }}" :active="request()->routeIs('reminders')">
                {{ __('Reminders') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('history') }}" :active="request()->routeIs('history')">
                {{ __('History') }}
            </x-responsive-nav-link>
        </div>
    </div>
</nav>
