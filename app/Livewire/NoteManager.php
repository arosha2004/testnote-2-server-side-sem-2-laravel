<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class NoteManager extends Component
{
    use WithPagination, WithFileUploads;

    public $categories;

    public $title;

    public $content;

    public $category_id;

    public $note_id;

    public $isOpen = false;

    public $isViewing = false;

    public $viewingNote = null;

    public $isConfirmingDelete = false;

    public $noteIdToDelete = null;

    public $search = '';

    public $filterCategory = '';

    // File attachment (temporary upload)
    public $attachment;
    // Existing stored attachment path for the note
    public $existing_attachment;

    protected $queryString = ['search', 'filterCategory'];

    public function mount()
    {
        $this->categories = Category::orderBy('category_name')->get();
    }

    public function render()
    {
        $query = auth()->user()->notes()->with('categories');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('content', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterCategory) {
            $query->whereHas('categories', function ($q) {
                $q->where('categories.id', $this->filterCategory);
            });
        }

        $notes = $query->latest()->paginate(9);

        return view('livewire.note-manager', ['notes' => $notes]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->content = '';
        $this->category_id = '';
        $this->note_id = '';
        $this->attachment = null;
        $this->existing_attachment = null;
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,md,rtf,odt|max:10240',
        ]);

        $note = auth()->user()->notes()->updateOrCreate(
            ['id' => $this->note_id],
            [
                'title' => $this->title,
                'content' => $this->content,
            ]
        );

        $note->categories()->sync(
            $this->category_id ? [$this->category_id] : []
        );

        // Handle uploaded attachment
        if ($this->attachment) {
            if ($this->existing_attachment) {
                Storage::disk('public')->delete($this->existing_attachment);
            }
            $path = $this->attachment->store('notes', 'public');
            $note->update(['attachment' => $path]);
        }

        $nextVersion = (int) $note->versions()->max('version_no') + 1;

        $note->versions()->create([
            'version_no' => $nextVersion,
            'updated_content' => $note->content,
            'updated_date' => now(),
        ]);

        session()->flash(
            'message',
            $this->note_id ? 'Note updated successfully.' : 'Note created successfully.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function view($id)
    {
        $this->viewingNote = auth()->user()->notes()->with('categories')->findOrFail($id);
        $this->isViewing = true;
    }

    public function closeViewModal()
    {
        $this->isViewing = false;
        $this->viewingNote = null;
    }

    public function edit($id)
    {
        $note = auth()->user()->notes()->with('categories')->findOrFail($id);
        $this->note_id = $id;
        $this->title = $note->title;
        $this->content = $note->content;
        $this->category_id = $note->categories->first()?->id;
        $this->existing_attachment = $note->attachment;
        if ($this->isViewing) {
            $this->closeViewModal();
        }
        $this->openModal();
    }

    public function confirmDelete($id)
    {
        $this->noteIdToDelete = $id;
        $this->isConfirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->isConfirmingDelete = false;
        $this->noteIdToDelete = null;
    }

    public function deleteNote()
    {
        if ($this->noteIdToDelete) {
            auth()->user()->notes()->findOrFail($this->noteIdToDelete)->delete();
            session()->flash('message', 'Note deleted successfully.');
            if ($this->isViewing && $this->viewingNote && $this->viewingNote->id == $this->noteIdToDelete) {
                $this->closeViewModal();
            }
            $this->cancelDelete();
        }
    }
}
