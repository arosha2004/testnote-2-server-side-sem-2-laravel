<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-10">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10">
        <div class="flex items-center gap-5">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-200">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Admin Control Center</h1>
                <p class="mt-1 text-sm font-medium text-slate-500">Monitor platform metrics, manage users, and oversee content.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                System Operational
            </span>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-8 flex items-center gap-3 rounded-2xl bg-emerald-50 p-4 text-emerald-800 border border-emerald-100 shadow-sm">
            <svg class="h-6 w-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p class="font-semibold text-sm">{{ session('message') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-8 flex items-center gap-3 rounded-2xl bg-rose-50 p-4 text-rose-800 border border-rose-100 shadow-sm">
            <svg class="h-6 w-6 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-13a1 1 0 112 0v4a1 1 0 11-2 0V5zm0 8a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/></svg>
            <p class="font-semibold text-sm">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        {{-- Card 1 --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white p-8 shadow-sm border border-slate-100 transition-all hover:shadow-md hover:border-slate-200 group">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-blue-50 transition-transform group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Users</p>
                    <h3 class="mt-2 text-4xl font-black text-slate-900 tracking-tight">{{ $totalUsers }}</h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-blue-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
        {{-- Card 2 --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white p-8 shadow-sm border border-slate-100 transition-all hover:shadow-md hover:border-slate-200 group">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-indigo-50 transition-transform group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Notes</p>
                    <h3 class="mt-2 text-4xl font-black text-slate-900 tracking-tight">{{ $totalNotes }}</h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>
        {{-- Card 3 --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white p-8 shadow-sm border border-slate-100 transition-all hover:shadow-md hover:border-slate-200 group">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-purple-50 transition-transform group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Categories</p>
                    <h3 class="mt-2 text-4xl font-black text-slate-900 tracking-tight">{{ $totalCategories }}</h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-purple-100 text-purple-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
        </div>
        {{-- Card 4 --}}
        <div class="relative overflow-hidden rounded-[2rem] bg-white p-8 shadow-sm border border-slate-100 transition-all hover:shadow-md hover:border-slate-200 group">
            <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-50 transition-transform group-hover:scale-150"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-wider">Reminders</p>
                    <h3 class="mt-2 text-4xl font-black text-slate-900 tracking-tight">{{ $totalReminders }}</h3>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-600">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10" wire:ignore x-data="{
        initCharts() {
            const userCtx = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: @js($userGrowthLabels),
                    datasets: [{
                        label: 'New Users',
                        data: @js($userGrowthData),
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { color: '#f1f5f9', borderDash: [4, 4] } },
                        x: { ticks: { color: '#94a3b8' }, grid: { display: false } }
                    }
                }
            });

            const noteCtx = document.getElementById('noteGrowthChart').getContext('2d');
            new Chart(noteCtx, {
                type: 'bar',
                data: {
                    labels: @js($noteGrowthLabels),
                    datasets: [{
                        label: 'Notes Created',
                        data: @js($noteGrowthData),
                        backgroundColor: '#8b5cf6',
                        borderRadius: 6,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { color: '#f1f5f9', borderDash: [4, 4] } },
                        x: { ticks: { color: '#94a3b8' }, grid: { display: false } }
                    }
                }
            });
        }
    }" x-init="
        if (typeof Chart === 'undefined') {
            let script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = () => initCharts();
            document.head.appendChild(script);
        } else {
            initCharts();
        }
    ">
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">User Registration Growth</h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">New accounts created over the last 7 days</p>
                </div>
                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Platform Activity</h3>
                    <p class="text-sm font-medium text-slate-500 mt-1">Total notes created over the last 7 days</p>
                </div>
                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="noteGrowthChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Search + Tabs --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <div class="flex gap-2 p-1 bg-slate-200/50 rounded-xl w-full sm:w-auto">
                <button wire:click="setTab('users')"
                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 {{ $activeTab === 'users' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Users
                </button>
                <button wire:click="setTab('notes')"
                    class="flex-1 sm:flex-none px-6 py-2.5 rounded-lg text-sm font-bold transition-all duration-200 {{ $activeTab === 'notes' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Notes
                </button>
            </div>
            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search records..." class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-medium text-slate-900 placeholder:text-slate-400 shadow-sm transition-all">
            </div>
        </div>

        {{-- Users Tab --}}
        @if($activeTab === 'users')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">User Details</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Contact</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Role</th>
                            <th class="px-8 py-5 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">Notes</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Joined</th>
                            <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-sm shadow-inner">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-900">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-slate-600">{{ $user->email }}</td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-500/20">Admin</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 ring-1 ring-inset ring-slate-500/10">User</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-center text-sm font-bold text-slate-700">{{ $user->notes_count }}</td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-slate-500">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if($user->role !== 'admin')
                                            <button wire:click="makeAdmin({{ $user->id }})" wire:confirm="Promote this user to Admin?" class="text-xs font-bold text-indigo-600 hover:bg-indigo-50 px-4 py-2 rounded-lg transition-colors ring-1 ring-inset ring-indigo-600/20">Promote</button>
                                        @else
                                            <button wire:click="removeAdmin({{ $user->id }})" wire:confirm="Remove admin role?" class="text-xs font-bold text-orange-600 hover:bg-orange-50 px-4 py-2 rounded-lg transition-colors ring-1 ring-inset ring-orange-600/20">Demote</button>
                                        @endif
                                        @if($user->id !== auth()->id())
                                            <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Delete this user and all their data?" class="text-xs font-bold text-rose-600 hover:bg-rose-50 px-4 py-2 rounded-lg transition-colors ring-1 ring-inset ring-rose-600/20">Delete</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        <p class="text-base font-semibold text-slate-900">No users found</p>
                                        <p class="text-sm mt-1">Try adjusting your search query.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/30">{{ $users->links() }}</div>
            @endif

        {{-- Notes Tab --}}
        @elseif($activeTab === 'notes')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Content</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Author</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Category</th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-8 py-5 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 bg-white">
                        @forelse($notes as $note)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="max-w-md">
                                        <p class="text-sm font-bold text-slate-900 truncate">{{ $note->title }}</p>
                                        <p class="text-xs font-medium text-slate-500 mt-1 truncate">{{ Str::limit($note->content, 80) }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-bold text-slate-700">
                                    {{ $note->user->name ?? 'Unknown User' }}
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @if($note->categories->isNotEmpty())
                                        <div class="flex gap-1.5 flex-wrap">
                                            @foreach($note->categories->take(2) as $category)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-indigo-50 text-indigo-700">
                                                    {{ $category->category_name }}
                                                </span>
                                            @endforeach
                                            @if($note->categories->count() > 2)
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-500">
                                                    +{{ $note->categories->count() - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-slate-400 text-xs font-medium italic">Uncategorized</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-slate-500">{{ $note->created_at->format('M d, Y') }}</td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button wire:click="deleteNote({{ $note->id }})" wire:confirm="Permanently delete this note?" class="text-xs font-bold text-rose-600 hover:bg-rose-50 px-4 py-2 rounded-lg transition-colors ring-1 ring-inset ring-rose-600/20">
                                            Delete Note
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="text-base font-semibold text-slate-900">No notes found</p>
                                        <p class="text-sm mt-1">There are no notes matching your search.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($notes->hasPages())
                <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/30">{{ $notes->links() }}</div>
            @endif
        @endif
    </div>
</div>
