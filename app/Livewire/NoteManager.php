<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Note;
use Livewire\Component;
use Livewire\WithPagination;

class NoteManager extends Component
{
    use WithPagination;

    public $categories;

    public $title;

    public $content;

    public $category_id;

    public $note_id;

    public $isOpen = false;

    public $search = '';

    public $filterCategory = '';

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
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
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

    public function edit($id)
    {
        $note = auth()->user()->notes()->with('categories')->findOrFail($id);
        $this->note_id = $id;
        $this->title = $note->title;
        $this->content = $note->content;
        $this->category_id = $note->categories->first()?->id;
        $this->openModal();
    }

    public function delete($id)
    {
        auth()->user()->notes()->findOrFail($id)->delete();
        session()->flash('message', 'Note deleted successfully.');
    }
}
