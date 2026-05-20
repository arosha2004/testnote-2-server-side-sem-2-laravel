<?php

namespace App\Livewire;

use App\Models\Reminder;
use Livewire\Component;

class DashboardStats extends Component
{
    public $totalNotes = 0;

    public $totalCategories = 0;

    public $totalReminders = 0;

    public $pinnedNotes = 0;

    public function render()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->totalNotes = $user->notes()->count();
            $this->totalCategories = $user->categories()->count();
            $this->pinnedNotes = $user->notes()->where('is_pinned', true)->count();
            $this->totalReminders = Reminder::whereHas('note', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('status', 'pending')->count();
        }

        return view('livewire.dashboard-stats');
    }
}
