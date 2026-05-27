<div class="space-y-6">
    {{-- Section header --}}
    <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 transition-colors">Recycle Bin</h2>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400 transition-colors">View, restore, or permanently delete your deleted notes</p>
        </div>
        @if($notes->count() > 0)
            <button wire:click="confirmEmptyTrash()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 active:scale-[0.98] dark:focus:ring-offset-slate-900 shrink-0">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Empty Recycle Bin
            </button>
        @endif
    </div>

    {{-- Toolbar --}}
    <div class="flex flex-col gap-3 sm:flex-row">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search deleted notes by title or content..."
                class="input-field pl-11"
            >
        </div>
        <div class="relative w-full sm:w-52">
            <select wire:model.live="filterCategory" class="input-field appearance-none pr-10">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-800/30 dark:bg-emerald-950/30 dark:text-emerald-400">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Trashed Notes Grid --}}
    @if($notes->count() > 0)
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($notes as $note)
                <article class="note-card group opacity-85 hover:opacity-100 transition-opacity">
                    <div class="flex flex-1 flex-col p-5">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            @if($note->categories->isNotEmpty())
                                <span class="badge-indigo truncate">{{ $note->categories->pluck('category_name')->join(', ') }}</span>
                            @else
                                <span class="badge">Uncategorized</span>
                            @endif
                            <span class="text-xs text-red-500 bg-red-50 dark:bg-red-950/40 dark:text-red-400 px-2 py-0.5 rounded-md font-semibold">Deleted</span>
                        </div>
                        <h3 class="mb-2 line-clamp-2 text-base font-semibold text-slate-900 dark:text-slate-100 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">{{ $note->title }}</h3>
                        <p class="line-clamp-3 flex-1 text-sm leading-relaxed text-slate-500 dark:text-slate-400 transition-colors">{{ $note->content }}</p>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 transition-colors px-5 py-3">
                        <span class="text-xs font-medium text-slate-400 dark:text-slate-500" title="Deleted date">
                            Deleted {{ $note->deleted_at->diffForHumans() }}
                        </span>
                        <div class="flex gap-2">
                            <button wire:click="confirmRestore({{ $note->id }})" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold text-brand-teal bg-teal-50 dark:bg-teal-950/40 dark:text-teal-400 hover:bg-brand-teal hover:text-white dark:hover:bg-brand-teal dark:hover:text-white transition-all shadow-sm" title="Restore note">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.568M12 8v4l3 3"/></svg>
                                Restore
                            </button>
                            <button wire:click="confirmDelete({{ $note->id }})" class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-bold text-red-600 bg-red-50 dark:bg-red-950/30 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all shadow-sm" title="Delete permanently">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="border-t border-slate-100 pt-6">
            {{ $notes->links() }}
        </div>
    @else
        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 transition-colors px-6 py-16 text-center">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-900 text-slate-400 dark:text-slate-500 transition-colors">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Recycle Bin is empty</h3>
            <p class="mt-2 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                @if($search || $filterCategory)
                    No deleted notes match your search. Try different filters.
                @else
                    Deleted notes will appear here. You can restore them anytime or choose to delete them permanently.
                @endif
            </p>
        </div>
    @endif

    {{-- Restore Confirmation Modal --}}
    @if($isConfirmingRestore)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="cancelRestore()"></div>
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-slate-900/5 transition-all">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-teal-50 dark:bg-teal-900/30 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-brand-teal sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6.568M12 8v4l3 3"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Restore note</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Are you sure you want to restore this note? It will be moved back to your main notes dashboard.</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row-reverse gap-3 bg-slate-50 dark:bg-slate-800/50 px-6 py-4">
                    <button wire:click="restoreNote()" class="rounded-xl bg-brand-teal px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-teal-700 transition-colors">
                        Restore
                    </button>
                    <button wire:click="cancelRestore()" class="rounded-xl bg-white dark:bg-slate-700 px-5 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Permanent Delete Confirmation Modal --}}
    @if($isConfirmingDelete)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="cancelDelete()"></div>
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-slate-900/5 transition-all">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Permanently delete note</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Are you sure you want to permanently delete this note? This action is permanent, cannot be undone, and you will lose all the content and versions associated with it.</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row-reverse gap-3 bg-slate-50 dark:bg-slate-800/50 px-6 py-4">
                    <button wire:click="deleteNotePermanently()" class="rounded-xl bg-red-600 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-700 transition-colors">
                        Delete Permanently
                    </button>
                    <button wire:click="cancelDelete()" class="rounded-xl bg-white dark:bg-slate-700 px-5 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Empty Trash Confirmation Modal --}}
    @if($isConfirmingEmptyTrash)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="cancelEmptyTrash()"></div>
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-slate-900/5 transition-all">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Empty Recycle Bin</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Are you sure you want to permanently delete all notes currently in the Recycle Bin? This action is permanent and cannot be undone.</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row-reverse gap-3 bg-slate-50 dark:bg-slate-800/50 px-6 py-4">
                    <button wire:click="emptyTrash()" class="rounded-xl bg-red-600 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-700 transition-colors">
                        Empty Recycle Bin
                    </button>
                    <button wire:click="cancelEmptyTrash()" class="rounded-xl bg-white dark:bg-slate-700 px-5 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
