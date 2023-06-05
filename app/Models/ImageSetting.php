<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'details',
        'type',
        'placeholder',
        'visibility',
        'categories',
        'description',
        'enabled'
    ];

    public $timestamps = false;

    public function storageProvider()
    {
        return $this->belongsTo(StorageProvider::class);
    }

    public function getDescriptionArray() {
        return explode(',', $this->description);
    }

    public function getCategoriesArray() {
        return explode(',', $this->categories);
    }
}