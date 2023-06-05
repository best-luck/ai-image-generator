<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'country_id',
        'percentage',
    ];

    /**
     * Relationships
     */
    public function country()
    {
        return $this->belongsTo(Country::class, );
    }
}
