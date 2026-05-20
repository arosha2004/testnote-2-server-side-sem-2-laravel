<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;

class CategoryManager extends Component
{
    public $categories;

    public $name;

    public $category_id;

    public $isOpen = false;

    public function render()
    {
        $this->categories = Category::withCount(['notes' => function ($query) {
            $query->where('user_id', auth()->id());
        }])->orderBy('category_name')->get();

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
        $this->category_id = '';
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::updateOrCreate(
            ['id' => $this->category_id ?: null],
            ['category_name' => $this->name]
        );

        session()->flash(
            'message',
            $this->category_id ? 'Category updated successfully.' : 'Category created successfully.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $cat = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $cat->category_name;
        $this->openModal();
    }

    public function delete($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('message', 'Category deleted successfully.');
    }
}
