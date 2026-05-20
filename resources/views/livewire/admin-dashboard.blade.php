<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-4 mb-8">
        <div class="bg-indigo-600 p-3 rounded-2xl text-white shadow-lg shadow-indigo-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Admin Control Center</h1>
            <p class="text-gray-500 font-medium">Manage users, notes, and platform statistics.</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p class="font-medium">{{ session('message') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-13a1 1 0 112 0v4a1 1 0 11-2 0V5zm0 8a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/></svg>
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition"></div>
            <div class="relative bg-white rounded-2xl p-6 border border-gray-100 flex items-center gap-4">
                <div class="bg-blue-50 p-3 rounded-xl text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Users</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition"></div>
            <div class="relative bg-white rounded-2xl p-6 border border-gray-100 flex items-center gap-4">
                <div class="bg-indigo-50 p-3 rounded-xl text-indigo-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Notes</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $totalNotes }}</h3>
                </div>
            </div>
        </div>
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition"></div>
            <div class="relative bg-white rounded-2xl p-6 border border-gray-100 flex items-center gap-4">
                <div class="bg-purple-50 p-3 rounded-xl text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Categories</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $totalCategories }}</h3>
                </div>
            </div>
        </div>
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-orange-600 to-amber-600 rounded-2xl blur opacity-20 group-hover:opacity-40 transition"></div>
            <div class="relative bg-white rounded-2xl p-6 border border-gray-100 flex items-center gap-4">
                <div class="bg-orange-50 p-3 rounded-xl text-orange-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Reminders</p>
                    <h3 class="text-3xl font-black text-gray-900 mt-1">{{ $totalReminders }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Search + Tabs --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 px-6 py-4 border-b border-gray-100">
            <div class="flex gap-2">
                <button wire:click="setTab('users')"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $activeTab === 'users' ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                    👤 Users
                </button>
                <button wire:click="setTab('notes')"
                    class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $activeTab === 'notes' ? 'bg-indigo-600 text-white shadow' : 'text-gray-600 hover:bg-gray-100' }}">
                    📝 Notes
                </button>
            </div>
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search..." class="w-full pl-9 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
            </div>
        </div>

        {{-- Users Tab --}}
        @if($activeTab === 'users')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-gray-900 text-sm">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">🛡️ Admin</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">👤 User</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->notes_count }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">
                                        @if($user->role !== 'admin')
                                            <button wire:click="makeAdmin({{ $user->id }})" wire:confirm="Promote this user to Admin?" class="text-xs font-medium text-purple-600 hover:bg-purple-50 px-3 py-1.5 rounded-lg transition">Promote</button>
                                        @else
                                            <button wire:click="removeAdmin({{ $user->id }})" wire:confirm="Remove admin role?" class="text-xs font-medium text-orange-600 hover:bg-orange-50 px-3 py-1.5 rounded-lg transition">Demote</button>
                                        @endif
                                        @if($user->id !== auth()->id())
                                            <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Delete this user and all their data?" class="text-xs font-medium text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">Delete</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">No users found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>

        {{-- Notes Tab --}}
        @elseif($activeTab === 'notes')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($notes as $note)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-gray-900 truncate max-w-xs">{{ $note->title }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-xs">{{ Str::limit($note->content, 60) }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $note->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @if($note->categories->isNotEmpty())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600">
                                            {{ $note->categories->pluck('category_name')->join(', ') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Uncategorized</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $note->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="deleteNote({{ $note->id }})" wire:confirm="Permanently delete this note?" class="text-xs font-medium text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No notes found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">{{ $notes->links() }}</div>
        @endif
    </div>
</div>
