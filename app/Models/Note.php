<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id', 'title', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function versions()
    {
        return $this->hasMany(NoteVersion::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}
