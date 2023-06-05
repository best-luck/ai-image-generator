<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'lang',
        'title',
        'image',
        'content',
    ];

    /**
     * Relationships
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'code');
    }
}