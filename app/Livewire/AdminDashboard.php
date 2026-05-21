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

    public $userGrowthLabels = [];
    public $userGrowthData = [];
    
    public $noteGrowthLabels = [];
    public $noteGrowthData = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadChartData();
    }

    public function loadStats()
    {
        $this->totalUsers      = User::count();
        $this->totalNotes      = Note::count();
        $this->totalCategories = Category::count();
        $this->totalReminders  = Reminder::count();
    }

    public function loadChartData()
    {
        // Get last 7 days of dates
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        $this->userGrowthLabels = $dates->map(fn($date) => \Carbon\Carbon::parse($date)->format('M d'))->toArray();
        $this->noteGrowthLabels = $this->userGrowthLabels;

        // Fetch user data
        $users = User::where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->get()
            ->groupBy(fn($u) => $u->created_at->format('Y-m-d'));
            
        $this->userGrowthData = $dates->map(fn($date) => $users->has($date) ? $users[$date]->count() : 0)->toArray();

        // Fetch note data
        $notes = Note::where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->get()
            ->groupBy(fn($n) => $n->created_at->format('Y-m-d'));

        $this->noteGrowthData = $dates->map(fn($date) => $notes->has($date) ? $notes[$date]->count() : 0)->toArray();
    }

    public function render()
    {
        $users = User::when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('first_name', 'like', '%'.$this->search.'%')
                        ->orWhere('last_name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->withCount('notes')
            ->latest()
            ->paginate(10);

        $notes = Note::with(['user', 'categories'])
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
