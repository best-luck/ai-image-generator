<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'checkout_id',
        'user_id',
        'image_id',
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
        'ip',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

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

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
      return $this->belongsTo(GeneratedImage::class, 'image_id', 'id');
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

    protected $casts = [
        'details_before_discount' => 'object',
        'details_after_discount' => 'object',
    ];
}
