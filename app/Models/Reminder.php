<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = ['note_id', 'reminder_time', 'repeat_type', 'status'];

    protected $casts = [
        'reminder_time' => 'datetime',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
