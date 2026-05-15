<?php

namespace App\Livewire;

use Livewire\Component;

class DashboardStats extends Component
{
    public $totalNotes = 0;
    public $totalCategories = 0;
    public $totalReminders = 0;

    public function render()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->totalNotes = $user->notes()->count();
            $this->totalCategories = $user->categories()->count();
            // Count reminders attached to notes owned by user
            $this->totalReminders = \App\Models\Reminder::whereHas('note', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();
        }

        return view('livewire.dashboard-stats');
    }
}
