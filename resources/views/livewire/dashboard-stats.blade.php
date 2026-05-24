<div>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 transition-colors">Overview</h2>
        <span class="text-xs text-slate-400 dark:text-slate-500 transition-colors">{{ now()->format('l, F j, Y') }}</span>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Notes --}}
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Total Notes</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100 transition-colors">{{ $totalNotes }}</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500 transition-colors">In your library</p>
                </div>
                <div class="stat-card-icon bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="mt-4 h-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700 transition-colors">
                <div class="h-full rounded-full bg-indigo-500 transition-all" style="width: {{ $totalNotes > 0 ? min(100, $totalNotes * 10) : 0 }}%"></div>
            </div>
        </div>

        {{-- Pinned --}}
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Pinned</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100 transition-colors">{{ $pinnedNotes }}</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500 transition-colors">Priority notes</p>
                </div>
                <div class="stat-card-icon bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                </div>
            </div>
            <div class="mt-4 h-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700 transition-colors">
                <div class="h-full rounded-full bg-amber-500 transition-all" style="width: {{ $totalNotes > 0 ? min(100, ($pinnedNotes / max($totalNotes, 1)) * 100) : 0 }}%"></div>
            </div>
        </div>

        {{-- Categories --}}
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Categories</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100 transition-colors">{{ $totalCategories }}</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500 transition-colors">Used in your notes</p>
                </div>
                <div class="stat-card-icon bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
            </div>
            <div class="mt-4 h-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700 transition-colors">
                <div class="h-full rounded-full bg-violet-500 transition-all" style="width: {{ $totalCategories > 0 ? min(100, $totalCategories * 20) : 0 }}%"></div>
            </div>
        </div>

        {{-- Reminders --}}
        <div class="stat-card">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 transition-colors">Pending Reminders</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100 transition-colors">{{ $totalReminders }}</p>
                    <p class="mt-1 text-xs text-slate-400 dark:text-slate-500 transition-colors">Awaiting action</p>
                </div>
                <div class="stat-card-icon bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4 h-1 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700 transition-colors">
                <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $totalReminders > 0 ? min(100, $totalReminders * 15) : 0 }}%"></div>
            </div>
        </div>
    </div>
</div>
