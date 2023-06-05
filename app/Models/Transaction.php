<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const STATUS_UNPAID = 0;
    const STATUS_PENDING = 1;
    const STATUS_PAID = 2;
    const STATUS_CANCELLED = 3;

    public function scopeUnpaid($query)
    {
        return $query->where('status', self::STATUS_UNPAID);
    }

    public function isUnpaid()
    {
        return $this->status == self::STATUS_UNPAID;
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function isPending()
    {
        return $this->status == self::STATUS_PENDING;
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function isPaid()
    {
        return $this->status == self::STATUS_PAID;
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function isCancelled()
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'checkout_id',
        'user_id',
        'plan_id',
        'coupon_id',
        'details_before_discount',
        'details_after_discount',
        'price',
        'tax',
        'fees',
        'total',
        'payment_gateway_id',
        'payment_id',
        'payer_id',
        'payer_email',
        'type',
        'status',
        'is_viewed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'details_before_discount' => 'object',
        'details_after_discount' => 'object',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
