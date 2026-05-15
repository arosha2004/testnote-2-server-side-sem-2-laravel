<div class="space-y-10">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-3xl flex items-center justify-center text-white shadow-2xl shadow-indigo-200">
                <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <h2 class="text-4xl font-black text-slate-900 tracking-tight">My Workspace</h2>
                <p class="text-slate-500 font-bold mt-1">Capture your genius thoughts here.</p>
            </div>
        </div>
        <button wire:click="create()" class="btn-indigo flex items-center gap-3">
            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Create New Note</span>
        </button>
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col md:flex-row gap-4 bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
        <div class="relative flex-grow group">
            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                <svg class="size-6 text-slate-400 group-focus-within:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search notes by title or keywords..." 
                   class="w-full pl-16 pr-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 shadow-inner font-medium text-lg transition-all placeholder:text-slate-400">
        </div>
        <div class="relative min-w-[220px]">
            <select wire:model.live="filterCategory" class="w-full h-full px-6 py-4 bg-slate-50 border-transparent rounded-2xl focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 font-bold text-slate-700 appearance-none cursor-pointer">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="bg-indigo-600 rounded-2xl p-4 text-white font-bold flex items-center gap-3 animate-bounce shadow-lg shadow-indigo-200">
            <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Notes Grid --}}
    @if($notes->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($notes as $note)
                <div class="group bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 hover:shadow-2xl hover:shadow-indigo-200 hover:-translate-y-2 transition-all duration-300 flex flex-col h-full overflow-hidden relative">
                    <!-- Top gradient accent -->
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <div class="p-8 flex-grow flex flex-col">
                        <div class="flex justify-between items-start mb-6">
                            @if($note->category)
                                <span class="px-4 py-1.5 rounded-xl text-xs font-black uppercase tracking-widest border" 
                                      style="background-color: {{ $note->category->color }}15; color: {{ $note->category->color }}; border-color: {{ $note->category->color }}30;">
                                    {{ $note->category->name }}
                                </span>
                            @else
                                <span class="px-4 py-1.5 rounded-xl text-xs font-black uppercase tracking-widest bg-slate-100 text-slate-500 border border-slate-200">
                                    Uncategorized
                                </span>
                            @endif
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 leading-tight mb-4 group-hover:text-indigo-600 transition-colors">{{ $note->title }}</h3>
                        <p class="text-slate-600 leading-relaxed line-clamp-4 font-medium text-base flex-grow">{{ $note->content }}</p>
                    </div>
                    
                    <div class="px-8 py-5 bg-slate-50 border-t border-slate-100 flex justify-between items-center group-hover:bg-indigo-50/30 transition-colors">
                        <div class="flex items-center gap-2 text-slate-400">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-sm font-bold">{{ $note->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="edit({{ $note->id }})" class="p-2.5 text-indigo-600 hover:bg-indigo-100 rounded-xl transition" title="Edit Note">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button wire:click="delete({{ $note->id }})" class="p-2.5 text-red-500 hover:bg-red-100 rounded-xl transition" title="Delete Note">
                                <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-12">
            {{ $notes->links() }}
        </div>
    @else
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl py-24 text-center">
            <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6 text-indigo-300">
                <svg class="size-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Your workspace is empty</h3>
            <p class="text-slate-500 font-bold mt-4 text-lg">Start your journey by creating your first note.</p>
        </div>
    @endif

    {{-- Modals remain the same but use premium classes --}}
    @if($isOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal()"></div>
            <div class="relative w-full max-w-3xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-10 text-white">
                    <h3 class="text-3xl font-black tracking-tight">{{ $note_id ? 'Edit Your Note' : 'New Thought' }}</h3>
                </div>
                <div class="p-10 space-y-8">
                    <div>
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 block">Note Title</label>
                        <input type="text" wire:model="title" class="w-full p-4 bg-slate-50 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 font-bold" placeholder="Give it a name...">
                    </div>
                    <div class="grid grid-cols-2 gap-8">
                         <div>
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 block">Category</label>
                            <select wire:model="category_id" class="w-full p-4 bg-slate-50 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 font-bold">
                                <option value="">None</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 block">Note Content</label>
                        <textarea wire:model="content" rows="10" class="w-full p-6 bg-slate-50 border-slate-100 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 font-medium" placeholder="Write something amazing..."></textarea>
                    </div>
                </div>
                <div class="px-10 py-8 bg-slate-50 flex justify-end gap-4">
                    <button wire:click="closeModal()" class="px-8 py-3 text-slate-500 font-black hover:text-slate-900 transition">Cancel</button>
                    <button wire:click.prevent="store()" class="btn-indigo">Save Note</button>
                </div>
            </div>
        </div>
    @endif
</div>
