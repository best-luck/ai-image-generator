<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'capital',
        'continent',
        'continent_code',
        'phone',
        'currency',
        'symbol',
        'alpha_3',
    ];
}
