<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
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