<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $notes = $request->user()->notes()->with('categories')->latest()->paginate(10);

        return response()->json($notes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $note = $request->user()->notes()->create([
            'title' => $validated['title'],
            'content' => $validated['content'] ?? null,
        ]);

        if (! empty($validated['category_id'])) {
            $note->categories()->sync([$validated['category_id']]);
        }

        $note->versions()->create([
            'version_no' => 1,
            'updated_content' => $note->content,
            'updated_date' => now(),
        ]);

        return response()->json($note->load('categories'), 201);
    }

    public function show(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $note->load(['categories', 'versions', 'reminders']);

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

        $note->update(collect($validated)->only(['title', 'content'])->all());

        if (array_key_exists('category_id', $validated)) {
            $note->categories()->sync(
                $validated['category_id'] ? [$validated['category_id']] : []
            );
        }

        if ($note->wasChanged(['title', 'content'])) {
            $nextVersion = (int) $note->versions()->max('version_no') + 1;

            $note->versions()->create([
                'version_no' => $nextVersion,
                'updated_content' => $note->content,
                'updated_date' => now(),
            ]);
        }

        return response()->json($note->load('categories'));
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
