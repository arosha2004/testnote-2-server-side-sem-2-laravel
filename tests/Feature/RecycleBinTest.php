<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Note;
use App\Livewire\NoteManager;
use App\Livewire\TrashManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RecycleBinTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_note_soft_deletes_it()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $note = Note::create([
            'user_id' => $user->id,
            'title' => 'Test Note',
            'content' => 'Test Content',
        ]);

        $this->assertNull($note->deleted_at);

        Livewire::test(NoteManager::class)
            ->set('noteIdToDelete', $note->id)
            ->call('deleteNote');

        $note = $note->fresh();
        $this->assertNotNull($note->deleted_at);
        $this->assertTrue($note->trashed());
    }

    public function test_trash_manager_displays_deleted_notes()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $note = Note::create([
            'user_id' => $user->id,
            'title' => 'Deleted Note Title',
            'content' => 'Test Content',
        ]);
        $note->delete();

        Livewire::test(TrashManager::class)
            ->assertSee('Deleted Note Title');
    }

    public function test_restoring_note_from_recycle_bin()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $note = Note::create([
            'user_id' => $user->id,
            'title' => 'Note to restore',
            'content' => 'Test Content',
        ]);
        $note->delete();

        $this->assertTrue($note->fresh()->trashed());

        Livewire::test(TrashManager::class)
            ->set('noteIdToRestore', $note->id)
            ->call('restoreNote');

        $this->assertFalse($note->fresh()->trashed());
        $this->assertNull($note->fresh()->deleted_at);
    }

    public function test_permanently_deleting_note()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $note = Note::create([
            'user_id' => $user->id,
            'title' => 'Note to force delete',
            'content' => 'Test Content',
        ]);
        $note->delete();

        Livewire::test(TrashManager::class)
            ->set('noteIdToDelete', $note->id)
            ->call('deleteNotePermanently');

        $this->assertNull($note->fresh());
    }

    public function test_emptying_trash()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $note1 = Note::create([
            'user_id' => $user->id,
            'title' => 'Note 1',
            'content' => 'Content 1',
        ]);
        $note1->delete();

        $note2 = Note::create([
            'user_id' => $user->id,
            'title' => 'Note 2',
            'content' => 'Content 2',
        ]);
        $note2->delete();

        $this->assertCount(2, Note::onlyTrashed()->get());

        Livewire::test(TrashManager::class)
            ->call('emptyTrash');

        $this->assertCount(0, Note::onlyTrashed()->get());
    }
}
