<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteVersion extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'note_id',
        'version_no',
        'updated_content',
        'updated_date',
    ];

    protected $casts = [
        'updated_date' => 'datetime',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }
}
