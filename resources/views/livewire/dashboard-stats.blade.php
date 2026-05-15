<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Total Notes -->
    <div class="card-premium p-8 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-2">My Library</p>
                <h3 class="text-5xl font-black text-slate-900 tracking-tight">{{ $totalNotes }}</h3>
                <p class="text-sm font-bold text-indigo-600 mt-2">Total Notes</p>
            </div>
            <div class="w-16 h-16 bg-indigo-50 rounded-[1.25rem] flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500">
                <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="card-premium p-8 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Organization</p>
                <h3 class="text-5xl font-black text-slate-900 tracking-tight">{{ $totalCategories }}</h3>
                <p class="text-sm font-bold text-purple-600 mt-2">Categories</p>
            </div>
            <div class="w-16 h-16 bg-purple-50 rounded-[1.25rem] flex items-center justify-center text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition-all duration-500">
                <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Active Reminders -->
    <div class="card-premium p-8 group">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Schedule</p>
                <h3 class="text-5xl font-black text-slate-900 tracking-tight">{{ $totalReminders }}</h3>
                <p class="text-sm font-bold text-orange-600 mt-2">Active Reminders</p>
            </div>
            <div class="w-16 h-16 bg-orange-50 rounded-[1.25rem] flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-all duration-500">
                <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>
</div>
