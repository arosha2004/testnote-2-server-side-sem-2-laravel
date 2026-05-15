<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = $request->user()->notes()->with('category')->latest()->paginate(10);
        return response()->json($notes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $note = $request->user()->notes()->create($validated);

        // Auto-save first version
        $note->versions()->create([
            'title' => $note->title,
            'content' => $note->content,
        ]);

        return response()->json($note, 201);
    }

    public function show(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->load(['category', 'versions', 'reminders']);
        return response()->json($note);
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $note->update($validated);

        // Save a new version if content changed
        if ($note->wasChanged(['title', 'content'])) {
            $note->versions()->create([
                'title' => $note->title,
                'content' => $note->content,
            ]);
        }

        return response()->json($note);
    }

    public function destroy(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->delete();

        return response()->json(null, 204);
    }
}
