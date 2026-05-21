<div class="space-y-6">
    {{-- Section header --}}
    <div class="flex flex-col gap-4 border-b border-slate-100 pb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">My Notes</h2>
            <p class="mt-0.5 text-sm text-slate-500">Create, search, and manage your notes</p>
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
                        <h3 class="mb-2 line-clamp-2 text-base font-semibold text-slate-900 group-hover:text-indigo-600">{{ $note->title }}</h3>
                        <p class="line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600">{{ $note->content }}</p>
                    </div>
                    <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 px-5 py-3">
                        <time class="text-xs font-medium text-slate-500">{{ $note->updated_at->diffForHumans() }}</time>
                        <div class="flex gap-1">
                            <button wire:click="edit({{ $note->id }})" class="rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-indigo-600" title="Edit">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="delete({{ $note->id }})" wire:confirm="Delete this note?" class="rounded-lg p-2 text-slate-500 transition hover:bg-white hover:text-red-600" title="Delete">
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
        <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 px-6 py-16 text-center">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-500">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900">No notes yet</h3>
            <p class="mt-2 max-w-sm text-sm text-slate-500">
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
            <div class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">{{ $note_id ? 'Edit note' : 'Create note' }}</h3>
                    <p class="mt-0.5 text-sm text-slate-500">{{ $note_id ? 'Update your note details below.' : 'Add a title, category, and content.' }}</p>
                </div>
                <div class="space-y-5 p-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Title</label>
                        <input type="text" wire:model="title" class="input-field" placeholder="Note title">
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Category</label>
                        <select wire:model="category_id" class="input-field">
                            <option value="">None</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Content</label>
                        <textarea wire:model="content" rows="8" class="input-field resize-y" placeholder="Write your note..."></textarea>
                        @error('content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Attachment (optional)</label>
                        <input type="file" wire:model="attachment" accept=".pdf,.doc,.docx,.txt,.md,.rtf,.odt" class="mt-1" />
                        @error('attachment') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                        @if($existing_attachment)
                            <p class="mt-2 text-sm text-slate-600">Current: <a href="{{ Storage::url($existing_attachment) }}" target="_blank" class="text-indigo-600 hover:underline">Download</a></p>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 bg-slate-50/50 px-6 py-4">
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
            <div class="relative w-full max-w-4xl min-h-[500px] max-h-[90vh] flex flex-col overflow-hidden rounded-[2rem] border border-white/20 bg-white shadow-2xl transition-all">
                
                {{-- Decorative Header Background --}}
                <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-br from-indigo-500/10 via-purple-500/10 to-pink-500/10 pointer-events-none"></div>
                
                <div class="relative flex justify-between items-start border-b border-slate-100/80 px-8 py-8 sm:px-10">
                    <div class="flex-1 pr-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-100">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </div>
                            @if($viewingNote->categories->isNotEmpty())
                                <span class="badge-indigo px-3 py-1 text-xs">{{ $viewingNote->categories->pluck('category_name')->join(', ') }}</span>
                            @else
                                <span class="badge px-3 py-1 text-xs">Uncategorized</span>
                            @endif
                        </div>
                        <h3 class="text-3xl sm:text-4xl font-black text-slate-900 leading-tight tracking-tight">{{ $viewingNote->title }}</h3>
                        <div class="mt-4 flex items-center gap-4 text-sm font-medium text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Updated {{ $viewingNote->updated_at->format('M d, Y \a\t h:i A') }}
                            </span>
                        </div>
                    </div>
                    <button wire:click="closeViewModal()" class="rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors bg-white/50 backdrop-blur-md">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <div class="relative flex-1 overflow-y-auto px-8 py-8 sm:px-10">
                    <div class="prose prose-lg prose-indigo max-w-none text-slate-700 leading-relaxed whitespace-pre-wrap font-medium">
                        {{ $viewingNote->content }}
                    </div>

                    @if($viewingNote->attachment)
                        <div class="mt-12 rounded-2xl border border-indigo-100 bg-indigo-50/50 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white shadow-sm text-indigo-600">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900">Attached Document</h4>
                                        <p class="text-xs font-medium text-slate-500 mt-0.5">Click to view or download</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($viewingNote->attachment) }}" target="_blank" class="rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-indigo-600 shadow-sm border border-indigo-100 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition-all">
                                    Open File
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-t border-slate-100 bg-slate-50/80 px-8 py-5 sm:px-10 gap-4">
                    <button wire:click="delete({{ $viewingNote->id }})" wire:confirm="Are you sure you want to delete this note?" class="flex items-center gap-2 text-sm font-bold text-red-500 hover:text-red-700 transition-colors order-2 sm:order-1 self-start sm:self-auto mt-2 sm:mt-0">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Delete Note
                    </button>
                    <div class="flex gap-4 order-1 sm:order-2 w-full sm:w-auto">
                        <button wire:click="closeViewModal()" class="flex-1 sm:flex-none rounded-xl px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            Close
                        </button>
                        <button wire:click="edit({{ $viewingNote->id }})" class="flex-1 sm:flex-none flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Note
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
