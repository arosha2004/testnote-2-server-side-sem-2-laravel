<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-6">
        <div class="flex items-center gap-4">
            <div class="bg-purple-600 p-3 rounded-2xl text-white shadow-lg shadow-purple-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Categories</h1>
                <p class="text-gray-500 font-medium">Organize your thoughts into colorful buckets.</p>
            </div>
        </div>
        <button wire:click="create()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-8 rounded-2xl shadow-xl shadow-indigo-200 transition-all duration-300 transform hover:-translate-y-1 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Category
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @forelse($categories as $cat)
            <div class="glass rounded-3xl shadow-sm card-hover overflow-hidden group border-0 ring-1 ring-white/50">
                <div class="h-2 bg-indigo-500"></div>
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-4 h-4 rounded-full border-2 border-white shadow-md bg-indigo-500"></div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $cat->category_name }}</h3>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">{{ $cat->notes_count }} {{ Str::plural('note', $cat->notes_count) }}</p>
                    <div class="flex space-x-2">
                        <button wire:click="edit({{ $cat->id }})" class="flex-1 text-center text-indigo-600 hover:bg-indigo-50 py-2 rounded-lg text-sm font-medium transition">Edit</button>
                        <button wire:click="delete({{ $cat->id }})" wire:confirm="Delete this category? Notes in it will become uncategorized." class="flex-1 text-center text-red-600 hover:bg-red-50 py-2 rounded-lg text-sm font-medium transition">Delete</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No categories yet</h3>
                <p class="mt-1 text-sm text-gray-500">Create categories to organize your notes.</p>
            </div>
        @endforelse
    </div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeModal()"></div>
            <div class="relative w-full max-w-md mx-4 z-50">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">
                                {{ $category_id ? '✏️ Edit Category' : '🏷️ New Category' }}
                            </h3>
                            <button wire:click="closeModal()" class="text-white/80 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Category Name</label>
                            <input type="text" wire:model="name" class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 shadow-sm" placeholder="e.g. Work, Personal, Study...">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">
                        <button wire:click="closeModal()" class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 font-medium transition">Cancel</button>
                        <button wire:click.prevent="store()" class="px-5 py-2.5 bg-purple-600 text-white rounded-xl hover:bg-purple-700 font-medium shadow-sm transition">
                            {{ $category_id ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
