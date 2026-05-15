<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;
use App\Models\NoteVersion;

class NoteHistory extends Component
{
    public $notes;
    public $selectedNote = null;
    public $versions = [];
    public $selectedNoteId = null;

    public function mount()
    {
        $this->notes = auth()->user()->notes()->select('id', 'title')->latest()->get();
    }

    public function render()
    {
        return view('livewire.note-history');
    }

    public function loadVersions($noteId)
    {
        // Verify ownership
        $note = auth()->user()->notes()->findOrFail($noteId);
        $this->selectedNote = $note;
        $this->selectedNoteId = $noteId;
        $this->versions = NoteVersion::where('note_id', $noteId)->latest()->get();
    }

    public function restore($versionId)
    {
        $version = NoteVersion::findOrFail($versionId);

        // Ensure the note belongs to user
        $note = auth()->user()->notes()->findOrFail($version->note_id);

        // Save current state as a version before restoring
        $note->versions()->create([
            'title' => $note->title,
            'content' => $note->content,
        ]);

        // Restore
        $note->update([
            'title' => $version->title,
            'content' => $version->content,
        ]);

        // Reload versions
        $this->versions = NoteVersion::where('note_id', $note->id)->latest()->get();
        $this->selectedNote = $note->fresh();

        session()->flash('message', 'Version restored successfully.');
    }
}
