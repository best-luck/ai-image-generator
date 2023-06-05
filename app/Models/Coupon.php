<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public function scopeValidCode($query, $code)
    {
        return $query->where('code', $code)
            ->where(function ($query) {
                $query->where('expiry_at', '>', Carbon::now());
            });
    }

    public function scopeValidForPlan($query, $planId)
    {
        return $query->where(function ($query) use ($planId) {
            $query->where('plan_id', $planId)
                ->orWhereNull('plan_id');
        });
    }

    public function isExpiry()
    {
        return Carbon::parse($this->expiry_at)->isPast();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'percentage',
        'plan_id',
        'action_type',
        'limit',
        'expiry_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expiry_at' => 'datetime',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}