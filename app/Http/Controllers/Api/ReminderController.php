<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        // Get all reminders for notes owned by the user
        $reminders = Reminder::whereHas('note', function($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->with('note')->latest('id')->paginate(10);
        
        return response()->json($reminders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'note_id' => 'required|exists:notes,id',
            'reminder_time' => 'required|date|after:now',
            'repeat_type' => 'nullable|string|in:none,daily,weekly,monthly',
        ]);

        // Ensure user owns the note
        $note = $request->user()->notes()->findOrFail($request->note_id);

        $reminder = $note->reminders()->create($validated);

        return response()->json($reminder, 201);
    }

    public function show(Request $request, Reminder $reminder)
    {
        // Ensure user owns the note associated with this reminder
        if ($reminder->note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($reminder);
    }

    public function update(Request $request, Reminder $reminder)
    {
        if ($reminder->note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'reminder_time' => 'sometimes|required|date',
            'repeat_type' => 'nullable|string|in:none,daily,weekly,monthly',
            'status' => 'nullable|string|in:pending,completed',
        ]);

        $reminder->update($validated);

        return response()->json($reminder);
    }

    public function destroy(Request $request, Reminder $reminder)
    {
        if ($reminder->note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $reminder->delete();

        return response()->json(null, 204);
    }
}
