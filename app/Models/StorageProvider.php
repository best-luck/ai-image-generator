<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorageProvider extends Model
{
    use HasFactory;

    public function isLocal()
    {
        return $this->alias == "local";
    }

    public function isDefault()
    {
        return $this->alias == env('FILESYSTEM_DRIVER');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'symbol',
        'logo',
        'credentials',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'credentials' => 'object',
    ];
}