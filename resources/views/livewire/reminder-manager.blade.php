<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-6">
        <div class="flex items-center gap-4">
            <div class="bg-orange-500 p-3 rounded-2xl text-white shadow-lg shadow-orange-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Reminders</h1>
                <p class="text-gray-500 font-medium">Never forget a thought or a task again.</p>
            </div>
        </div>
        <button wire:click="create()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold py-4 px-8 rounded-2xl shadow-xl shadow-indigo-200 transition-all duration-300 transform hover:-translate-y-1 flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Reminder
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-xl shadow-sm">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <p class="font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-orange-50 to-amber-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Note</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Reminder Time</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Repeat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($reminders as $reminder)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $reminder->note->title }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">{{ $reminder->reminder_time->format('M d, Y — h:i A') }}</div>
                                <div class="text-xs text-gray-400">{{ $reminder->reminder_time->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                    {{ $reminder->repeat_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($reminder->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">✓ Completed</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">⏳ Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($reminder->status !== 'completed')
                                        <button wire:click="markComplete({{ $reminder->id }})" class="text-green-600 hover:bg-green-50 p-2 rounded-lg transition-colors" title="Mark Complete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button wire:click="edit({{ $reminder->id }})" class="text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                    @endif
                                    <button wire:click="delete({{ $reminder->id }})" wire:confirm="Delete this reminder?" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No reminders yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Set reminders to never miss important notes.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" wire:click="closeModal()"></div>
            <div class="relative w-full max-w-lg mx-4 z-50">
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-5">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xl font-bold text-white">{{ $reminder_id ? '✏️ Edit Reminder' : '⏰ New Reminder' }}</h3>
                            <button wire:click="closeModal()" class="text-white/80 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Note</label>
                            <select wire:model="note_id" class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                                <option value="">Select a Note</option>
                                @foreach($notes as $note)
                                    <option value="{{ $note->id }}">{{ $note->title }}</option>
                                @endforeach
                            </select>
                            @error('note_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Reminder Date & Time</label>
                            <input type="datetime-local" wire:model="reminder_time" class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                            @error('reminder_time') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Repeat</label>
                            <select wire:model="repeat_type" class="w-full border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm">
                                <option value="none">No Repeat</option>
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t">
                        <button wire:click="closeModal()" class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 font-medium transition">Cancel</button>
                        <button wire:click.prevent="store()" class="px-5 py-2.5 bg-orange-500 text-white rounded-xl hover:bg-orange-600 font-medium shadow-sm transition">
                            {{ $reminder_id ? 'Update' : 'Set Reminder' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
