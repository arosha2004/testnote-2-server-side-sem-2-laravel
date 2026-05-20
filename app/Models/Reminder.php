<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'note_id',
        'status',
        'notification_type',
        'reminder_date_time',
        'repeat_type',
    ];

    protected $casts = [
        'reminder_date_time' => 'datetime',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
