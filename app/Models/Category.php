<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['category_name'];

    /**
     * Alias for views and API that still use `name`.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->category_name,
            set: fn (?string $value) => ['category_name' => $value],
        );
    }

    public function notes()
    {
        return $this->belongsToMany(Note::class, 'note_category')
            ->withPivot('description');
    }
}
