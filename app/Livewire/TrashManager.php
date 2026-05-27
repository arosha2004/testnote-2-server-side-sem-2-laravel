<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class TrashManager extends Component
{
    use WithPagination;

    public $categories;

    public $search = '';

    public $filterCategory = '';

    // Confirmation states
    public $isConfirmingRestore = false;
    public $noteIdToRestore = null;

    public $isConfirmingDelete = false;
    public $noteIdToDelete = null;

    public $isConfirmingEmptyTrash = false;

    protected $queryString = ['search', 'filterCategory'];

    public function mount()
    {
        $this->categories = Category::orderBy('category_name')->get();
    }

    public function render()
    {
        $query = auth()->user()->notes()->onlyTrashed()->with('categories');

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

        $notes = $query->latest('deleted_at')->paginate(9);

        return view('livewire.trash-manager', ['notes' => $notes]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    // --- Restore Actions ---
    public function confirmRestore($id)
    {
        $this->noteIdToRestore = $id;
        $this->isConfirmingRestore = true;
    }

    public function cancelRestore()
    {
        $this->isConfirmingRestore = false;
        $this->noteIdToRestore = null;
    }

    public function restoreNote()
    {
        if ($this->noteIdToRestore) {
            $note = auth()->user()->notes()->onlyTrashed()->findOrFail($this->noteIdToRestore);
            $note->restore();
            session()->flash('message', 'Note restored successfully.');
            $this->cancelRestore();
        }
    }

    // --- Permanent Delete Actions ---
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

    public function deleteNotePermanently()
    {
        if ($this->noteIdToDelete) {
            $note = auth()->user()->notes()->onlyTrashed()->findOrFail($this->noteIdToDelete);
            
            // Delete file attachment if it exists
            if ($note->attachment) {
                Storage::disk('public')->delete($note->attachment);
            }
            
            $note->forceDelete();
            session()->flash('message', 'Note permanently deleted.');
            $this->cancelDelete();
        }
    }

    // --- Empty Trash Actions ---
    public function confirmEmptyTrash()
    {
        $this->isConfirmingEmptyTrash = true;
    }

    public function cancelEmptyTrash()
    {
        $this->isConfirmingEmptyTrash = false;
    }

    public function emptyTrash()
    {
        $trashedNotes = auth()->user()->notes()->onlyTrashed()->get();
        
        foreach ($trashedNotes as $note) {
            if ($note->attachment) {
                Storage::disk('public')->delete($note->attachment);
            }
            $note->forceDelete();
        }
        
        session()->flash('message', 'Recycle bin cleared successfully.');
        $this->cancelEmptyTrash();
        $this->resetPage();
    }
}
