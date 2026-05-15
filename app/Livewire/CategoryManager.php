<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryManager extends Component
{
    public $categories;
    public $name, $color = '#6366f1', $category_id;
    public $isOpen = false;

    public function render()
    {
        $this->categories = auth()->user()->categories()->withCount('notes')->get();
        return view('livewire.category-manager');
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
        $this->name = '';
        $this->color = '#6366f1';
        $this->category_id = '';
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        auth()->user()->categories()->updateOrCreate(['id' => $this->category_id], [
            'name' => $this->name,
            'color' => $this->color,
        ]);

        session()->flash('message',
            $this->category_id ? 'Category updated successfully.' : 'Category created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $cat = auth()->user()->categories()->findOrFail($id);
        $this->category_id = $id;
        $this->name = $cat->name;
        $this->color = $cat->color;
        $this->openModal();
    }

    public function delete($id)
    {
        auth()->user()->categories()->findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully.');
    }
}
