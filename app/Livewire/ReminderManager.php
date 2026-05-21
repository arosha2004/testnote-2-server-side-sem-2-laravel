<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reminder;
use App\Models\Note;

class ReminderManager extends Component
{
    public $reminders, $notes;
    public $note_id, $reminder_time, $repeat_type = 'none', $reminder_id;
    public $isOpen = false;

    public function mount()
    {
        $this->notes = auth()->user()->notes;
    }

    public function render()
    {
        $this->reminders = Reminder::whereHas('note', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('note')->latest()->get();

        return view('livewire.reminder-manager');
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
        $this->note_id = '';
        $this->reminder_time = '';
        $this->repeat_type = 'none';
        $this->reminder_id = '';
        $this->resetValidation();
    }

    public function store()
    {
        $this->validate([
            'note_id' => 'required|exists:notes,id',
            'reminder_time' => 'required|date|after:now',
            'repeat_type' => 'required|in:none,daily,weekly,monthly',
        ]);

        // Verify ownership
        auth()->user()->notes()->findOrFail($this->note_id);

        Reminder::updateOrCreate(['id' => $this->reminder_id], [
            'note_id' => $this->note_id,
            'reminder_date_time' => $this->reminder_time,
            'repeat_type' => $this->repeat_type,
            'status' => 'pending',
        ]);

        session()->flash('message',
            $this->reminder_id ? 'Reminder updated successfully.' : 'Reminder created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $reminder = Reminder::whereHas('note', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $this->reminder_id = $id;
        $this->note_id = $reminder->note_id;
        $this->reminder_time = $reminder->reminder_date_time->format('Y-m-d\TH:i');
        $this->repeat_type = $reminder->repeat_type;
        $this->openModal();
    }

    public function markComplete($id)
    {
        $reminder = Reminder::whereHas('note', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $reminder->update(['status' => 'completed']);
        session()->flash('message', 'Reminder marked as completed.');
    }

    public function delete($id)
    {
        $reminder = Reminder::whereHas('note', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $reminder->delete();
        session()->flash('message', 'Reminder deleted successfully.');
    }
}
