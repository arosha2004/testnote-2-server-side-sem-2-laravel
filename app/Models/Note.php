<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'content', 'is_pinned'];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'note_category')
            ->withPivot('description');
    }

    public function versions()
    {
        return $this->hasMany(NoteVersion::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Derived attribute: Word_count (not stored in the database).
     */
    public function getWordCountAttribute(): int
    {
        return str_word_count(strip_tags((string) $this->content));
    }
}
