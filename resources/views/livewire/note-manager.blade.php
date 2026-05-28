<div class="space-y-6">
    {{-- Section header --}}
    <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 transition-colors">My Notes</h2>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400 transition-colors">Create, search, and manage your notes</p>
        </div>
        <button wire:click="create()" class="btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Note
        </button>
    </div>

    {{-- Toolbar --}}
    <div class="flex flex-col gap-3 sm:flex-row">
        <div class="relative flex-1">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search by title or content..."
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
        <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Notes grid --}}
    @if($notes->count() > 0)
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($notes as $note)
                <article class="note-card group">
                    <div class="flex flex-1 flex-col p-5 cursor-pointer" wire:click="view({{ $note->id }})">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            @if($note->categories->isNotEmpty())
                                <span class="badge-indigo truncate">{{ $note->categories->pluck('category_name')->join(', ') }}</span>
                            @else
                                <span class="badge">Uncategorized</span>
                            @endif
                            @if($note->is_pinned)
                                <span class="shrink-0 text-amber-500" title="Pinned">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                                </span>
                            @endif
                        </div>
                        <h3 class="mb-2 line-clamp-2 text-base font-semibold text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $note->title }}</h3>
                        <p class="line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600 dark:text-slate-400 transition-colors">{{ $note->content }}</p>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 transition-colors px-5 py-3">
                        <time class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $note->updated_at->diffForHumans() }}</time>
                        <div class="flex gap-1">
                            <button wire:click="togglePin({{ $note->id }})" class="rounded-lg p-2 {{ $note->is_pinned ? 'text-amber-500 hover:text-amber-600' : 'text-slate-500 dark:text-slate-400 hover:text-amber-500' }} transition hover:bg-white dark:hover:bg-slate-700" title="{{ $note->is_pinned ? 'Unpin' : 'Pin' }}">
                                <svg class="h-4 w-4" fill="{{ $note->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            </button>
                            <button wire:click="edit({{ $note->id }})" class="rounded-lg p-2 text-slate-500 dark:text-slate-400 transition hover:bg-white dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400" title="Edit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="exportPdf({{ $note->id }})" class="rounded-lg p-2 text-slate-500 dark:text-slate-400 transition hover:bg-white dark:hover:bg-slate-700 hover:text-emerald-600 dark:hover:text-emerald-400" title="Export PDF">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </button>
                            <button wire:click="confirmDelete({{ $note->id }})" class="rounded-lg p-2 text-slate-500 dark:text-slate-400 transition hover:bg-white dark:hover:bg-slate-700 hover:text-red-600 dark:hover:text-red-400" title="Delete">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 dark:text-indigo-400 transition-colors">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">No notes yet</h3>
            <p class="mt-2 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                @if($search || $filterCategory)
                    No notes match your search. Try different keywords or clear filters.
                @else
                    Get started by creating your first note. Organize ideas with categories and reminders.
                @endif
            </p>
            @if(!$search && !$filterCategory)
                <button wire:click="create()" class="btn-primary mt-6">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create your first note
                </button>
            @endif
        </div>
    @endif

    {{-- Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" wire:click="closeModal()"></div>
            <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl transition-colors">
                <div class="border-b border-slate-100 dark:border-slate-700 px-6 py-5 transition-colors">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 transition-colors">{{ $note_id ? 'Edit note' : 'Create note' }}</h3>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400 transition-colors">{{ $note_id ? 'Update your note details below.' : 'Add a title, category, and content.' }}</p>
                </div>
                <div class="space-y-5 p-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors">Title</label>
                        <input type="text" wire:model="title" class="input-field" placeholder="Note title">
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors">Category</label>
                        <select wire:model="category_id" class="input-field">
                            <option value="">None</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors">Content</label>
                        <textarea wire:model="content" rows="8" class="input-field resize-y" placeholder="Write your note..."></textarea>
                        @error('content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors">Attachment (optional)</label>
                        <input type="file" wire:model="attachment" accept=".pdf,.doc,.docx,.txt,.md,.rtf,.odt" class="mt-1 block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-slate-700 dark:file:text-slate-300 dark:hover:file:bg-slate-600 transition-colors" />
                        @error('attachment') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                        @if($existing_attachment)
                            <p class="mt-2 text-sm text-slate-600">Current: <a href="{{ Storage::url($existing_attachment) }}" target="_blank" class="text-indigo-600 hover:underline">Download</a></p>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 px-6 py-4 transition-colors">
                    <button wire:click="closeModal()" class="btn-secondary">Cancel</button>
                    <button wire:click.prevent="store()" class="btn-primary">Save note</button>
                </div>
            </div>
        </div>
    @endif

    {{-- View Modal --}}
    @if($isViewing && $viewingNote)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="closeViewModal()"></div>
            <div class="relative w-full max-w-4xl min-h-[500px] max-h-[90vh] flex flex-col overflow-hidden rounded-[2rem] border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-2xl transition-all">
                
                {{-- Decorative Header Background --}}
                <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-br from-indigo-500/10 via-purple-500/10 to-pink-500/10 pointer-events-none"></div>
                
                <div class="relative flex justify-between items-start border-b border-slate-100 dark:border-slate-700/80 px-8 py-8 sm:px-10">
                    <div class="flex-1 pr-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 shadow-sm border border-indigo-100 dark:border-indigo-900/50">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            @if($viewingNote->categories->isNotEmpty())
                                <span class="badge-indigo px-3 py-1 text-xs">{{ $viewingNote->categories->pluck('category_name')->join(', ') }}</span>
                            @else
                                <span class="badge px-3 py-1 text-xs">Uncategorized</span>
                            @endif
                        </div>
                        <h3 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white leading-tight tracking-tight">{{ $viewingNote->title }}</h3>
                        <div class="mt-4 flex items-center gap-4 text-sm font-medium text-slate-500 dark:text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Updated {{ $viewingNote->updated_at->format('M d, Y \a\t h:i A') }}
                            </span>
                        </div>
                    </div>
                    <button wire:click="closeViewModal()" class="rounded-full p-2 text-slate-400 dark:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-200 transition-colors bg-white/50 dark:bg-slate-800/50 backdrop-blur-md">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="relative flex-1 overflow-y-auto px-8 py-8 sm:px-10">
                    <div class="prose prose-lg prose-indigo dark:prose-invert max-w-none text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap font-medium">
                        {{ $viewingNote->content }}
                    </div>

                    @if($viewingNote->attachment)
                        <div class="mt-12 rounded-2xl border border-indigo-100 dark:border-indigo-900/50 bg-indigo-50/50 dark:bg-indigo-950/20 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white dark:bg-slate-800 shadow-sm text-indigo-600 dark:text-indigo-400">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Attached Document</h4>
                                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Click to view or download</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($viewingNote->attachment) }}" target="_blank" class="rounded-xl bg-white dark:bg-slate-800 px-5 py-2.5 text-sm font-bold text-indigo-600 dark:text-indigo-400 shadow-sm border border-indigo-100 dark:border-indigo-900/50 hover:bg-indigo-600 dark:hover:bg-indigo-600 hover:text-white dark:hover:text-white hover:border-indigo-600 dark:hover:border-indigo-600 transition-all">
                                    Open File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-t border-slate-100 dark:border-slate-700/80 bg-slate-50/80 dark:bg-slate-850/80 px-8 py-5 sm:px-10 gap-4">
                    <button wire:click="confirmDelete({{ $viewingNote->id }})" class="flex items-center gap-2 text-sm font-bold text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors order-2 sm:order-1 self-start sm:self-auto mt-2 sm:mt-0">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete Note
                    </button>
                    <div class="flex gap-4 order-1 sm:order-2 w-full sm:w-auto">
                        <button wire:click="closeViewModal()" class="flex-1 sm:flex-none rounded-xl px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-600 hover:text-slate-900 dark:hover:text-white transition-colors">
                            Close
                        </button>
                        <button wire:click="exportPdf({{ $viewingNote->id }})" class="flex-1 sm:flex-none flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-emerald-200 dark:shadow-none hover:bg-emerald-700 hover:-translate-y-0.5 transition-all">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Export PDF
                        </button>
                        <button wire:click="edit({{ $viewingNote->id }})" class="flex-1 sm:flex-none flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 dark:shadow-none hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Note
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($isConfirmingDelete)
        <div class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" wire:click="cancelDelete()"></div>
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl bg-white dark:bg-slate-800 shadow-2xl ring-1 ring-slate-900/5 transition-all">
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Delete note</h3>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Are you sure you want to delete this note? It will be moved to the Recycle Bin and can be restored later.</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-row-reverse gap-3 bg-slate-50 dark:bg-slate-800/50 px-6 py-4">
                    <button wire:click="deleteNote()" class="rounded-xl bg-red-600 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                    <button wire:click="cancelDelete()" class="rounded-xl bg-white dark:bg-slate-700 px-5 py-2 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
