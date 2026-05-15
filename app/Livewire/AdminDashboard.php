<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Note;
use App\Models\Category;
use App\Models\Reminder;

class AdminDashboard extends Component
{
    use WithPagination;

    public $totalUsers, $totalNotes, $totalCategories, $totalReminders;
    public $search = '';
    public $activeTab = 'users';

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalUsers      = User::count();
        $this->totalNotes      = Note::count();
        $this->totalCategories = Category::count();
        $this->totalReminders  = Reminder::count();
    }

    public function render()
    {
        $users = User::when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->withCount('notes')
            ->latest()
            ->paginate(10);

        $notes = Note::with(['user', 'category'])
            ->when($this->search, function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin-dashboard', compact('users', 'notes'));
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            session()->flash('error', 'Cannot delete an admin account.');
            return;
        }
        $user->delete();
        $this->loadStats();
        session()->flash('message', 'User deleted successfully.');
    }

    public function deleteNote($id)
    {
        Note::findOrFail($id)->delete();
        $this->loadStats();
        session()->flash('message', 'Note deleted successfully.');
    }

    public function makeAdmin($id)
    {
        User::findOrFail($id)->update(['role' => 'admin']);
        session()->flash('message', 'User promoted to Admin.');
    }

    public function removeAdmin($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot remove your own admin role.');
            return;
        }
        $user->update(['role' => 'user']);
        session()->flash('message', 'Admin role removed.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
