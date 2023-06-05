<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory, Sluggable;
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'lang',
        'name',
        'slug',
        'views',
    ];

    /**
     * Relationships
     */
    public function blogArticles()
    {
        return $this->hasMany(BlogArticle::class, 'id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'code');
    }

}
