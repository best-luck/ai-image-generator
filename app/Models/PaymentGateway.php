<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    public function scopeHasCurrency($query)
    {
        return $query->whereRaw('JSON_CONTAINS(supported_currencies, JSON_ARRAY(?))', [settings('currency')->code]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'logo',
        'fees',
        'test_mode',
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
        'supported_currencies' => 'array',
    ];
}