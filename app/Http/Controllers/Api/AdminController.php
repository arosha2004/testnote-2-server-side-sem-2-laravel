<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Note;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_notes' => Note::count(),
            'total_categories' => \App\Models\Category::count(),
            'total_reminders' => \App\Models\Reminder::count(),
        ]);
    }

    public function users()
    {
        return response()->json(User::withCount('notes')->latest()->paginate(10));
    }

    public function notes()
    {
        return response()->json(Note::with('user', 'category')->latest()->paginate(10));
    }

    public function promote(User $user)
    {
        $user->update(['role' => 'admin']);
        return response()->json(['message' => 'User promoted to admin']);
    }

    public function demote(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot demote yourself'], 403);
        }
        $user->update(['role' => 'user']);
        return response()->json(['message' => 'User demoted to user']);
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot delete yourself'], 403);
        }
        $user->delete();
        return response()->json(['message' => 'User and their data deleted']);
    }

    public function deleteNote(Note $note)
    {
        $note->delete();
        return response()->json(['message' => 'Note deleted by admin']);
    }
}
