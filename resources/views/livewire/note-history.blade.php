<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-4 mb-8">
        <div class="bg-purple-600 p-3 rounded-2xl text-white shadow-lg shadow-purple-200">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Note History</h1>
            <p class="text-gray-500 font-medium">Browse and restore previous versions of your notes.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Notes List --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-5 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Select a Note</h3>
            </div>
            <div class="divide-y divide-gray-50 max-h-[500px] overflow-y-auto">
                @forelse($notes as $note)
                    <button wire:click="loadVersions({{ $note->id }})"
                        class="w-full text-left px-5 py-4 hover:bg-indigo-50 transition-colors {{ $selectedNoteId == $note->id ? 'bg-indigo-50 border-l-4 border-indigo-500' : '' }}">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $note->title }}</p>
                    </button>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-500">No notes found.</div>
                @endforelse
            </div>
        </div>

        {{-- Versions Panel --}}
        <div class="lg:col-span-2">
            @if($selectedNote)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-4">
                        <h3 class="font-bold text-white">Versions of: {{ $selectedNote->title }}</h3>
                        <p class="text-indigo-100 text-sm mt-0.5">Current content shown below. Click restore to revert.</p>
                    </div>

                    {{-- Current Version --}}
                    <div class="px-5 py-4 bg-indigo-50 border-b border-indigo-100">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-600 text-white">Current</span>
                            <span class="text-xs text-gray-500">{{ $selectedNote->updated_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 mb-1">{{ $selectedNote->title }}</p>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $selectedNote->content }}</p>
                    </div>

                    {{-- Version History --}}
                    <div class="divide-y divide-gray-50 max-h-[400px] overflow-y-auto">
                        @forelse($versions as $version)
                            <div class="px-5 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">v{{ $loop->remaining + 1 }}</span>
                                            <span class="text-xs text-gray-400">{{ $version->created_at->diffForHumans() }} · {{ $version->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $version->title }}</p>
                                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $version->content }}</p>
                                    </div>
                                    <button wire:click="restore({{ $version->id }})"
                                        wire:confirm="Restore this version? The current content will be saved first."
                                        class="shrink-0 text-xs font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition">
                                        Restore
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-8 text-center text-sm text-gray-500">No version history yet.</div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center h-64">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="mt-3 text-gray-500">Select a note to view its version history</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
