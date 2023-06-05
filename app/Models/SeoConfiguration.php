<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoConfiguration extends Model
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
        'description',
        'keywords',
        'robots_index',
        'robots_follow_links',
    ];

    /**
     * Relationships
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'code');
    }
}
