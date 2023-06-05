<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 1;
    public const STATUS_CANCELLED = 0;

    public function scopeExpired($query)
    {
        $query->where('status', self::STATUS_ACTIVE)
            ->where('expiry_at', '<', Carbon::now());
    }

    public function isExpired()
    {
        return $this->status == self::STATUS_ACTIVE &&
        !$this->plan->isFree() && $this->expiry_at < Carbon::now();
    }

    public function scopeIsAboutToExpire($query)
    {
        $now = Carbon::now();
        $expiryThreshold = $now->copy()->addDays(settings('subscription')->about_to_expire_reminder);
        return $query->whereHas('plan', function ($planQuery) {
            $planQuery->where('is_free', false);
        })->where('status', self::STATUS_ACTIVE)->where('expiry_at', '>=', $now)->where('expiry_at', '<=', $expiryThreshold);
    }

    public function isAboutToExpire()
    {
        $expiry = Carbon::parse($this->expiry_at);
        $now = Carbon::now();
        $daysLeft = $expiry->diffInDays($now);
        return !$this->plan->isFree() && $daysLeft <= settings('subscription')->about_to_expire_reminder && $now < $expiry;
    }

    public function scopeFree($query)
    {
        $query->whereHas('plan', function ($query) {
            $query->where('is_free', true);
        });
    }

    public function scopeNotFree($query)
    {
        $query->whereHas('plan', function ($query) {
            $query->where('is_free', false);
        });
    }

    public function isFree()
    {
        return $this->plan->isFree();
    }

    public function scopeActive($query)
    {
        $query->where('status', self::STATUS_ACTIVE)
            ->where('expiry_at', '>', Carbon::now());
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE && $this->expiry_at > Carbon::now();
    }

    public function scopeCancelled($query)
    {
        $query->where('status', self::STATUS_CANCELLED);
    }

    public function isCancelled()
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    public function scopeAboutToExpireReminderNotSent($query)
    {
        return $query->where('about_to_expire_reminder', false);
    }

    public function scopeExpiredReminderNotSent($query)
    {
        return $query->where('expired_reminder', false);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'plan_id',
        'generated_images',
        'status',
        'expiry_at',
        'about_to_expire_reminder',
        'expired_reminder',
        'is_viewed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expiry_at' => 'datetime',
        'generated_images' => 'int',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

}