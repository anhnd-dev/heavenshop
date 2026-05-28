<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | ORDER STATUS
    |--------------------------------------------------------------------------
    */

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';

    /*
    |--------------------------------------------------------------------------
    | PAYMENT STATUS
    |--------------------------------------------------------------------------
    */

    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    /*
    |--------------------------------------------------------------------------
    | PAYMENT METHOD
    |--------------------------------------------------------------------------
    */

    const PAYMENT_COD = 'cod';
    const PAYMENT_VNPAY = 'vnpay';
    const PAYMENT_MOMO = 'momo';
    const PAYMENT_ZALOPAY = 'zalopay';

    protected $fillable = [

        'order_code',

        'customer_id',
        'coupon_id',
        'customer_address_id',

        // shipping snapshot
        'shipping_name',
        'shipping_phone',
        'shipping_email',

        'shipping_province',
        'shipping_district',
        'shipping_ward',
        'shipping_address',

        'note',

        // shipping
        'shipping_method',
        'shipping_fee',

        // payment
        'payment_method',
        'payment_status',

        // order
        'order_status',

        // money
        'subtotal',
        'discount_amount',
        'grand_total',

        // timeline
        'paid_at',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'returned_at',
        'refunded_at',

        'payment_deadline',

        // cancel
        'cancel_reason',
    ];

    protected $casts = [

        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',

        'paid_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'returned_at' => 'datetime',
        'refunded_at' => 'datetime',
        'payment_deadline' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(
            Customer::class
        );
    }

    public function customerAddress()
    {
        return $this->belongsTo(
            CustomerAddress::class
        );
    }

    public function coupon()
    {
        return $this->belongsTo(
            Coupon::class
        );
    }

    public function items()
    {
        return $this->hasMany(
            OrderItem::class
        );
    }

    public function paymentTransactions()
    {
        return $this->hasMany(
            PaymentTransaction::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getFullAddressAttribute(): string
    {
        return implode(', ', [

            $this->shipping_address,

            $this->shipping_ward,

            $this->shipping_district,

            $this->shipping_province,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isPaid(): bool
    {
        return $this->payment_status
            === self::PAYMENT_PAID;
    }

    public function isPending(): bool
    {
        return $this->order_status
            === self::STATUS_PENDING;
    }

    public function isDelivered(): bool
    {
        return $this->order_status
            === self::STATUS_DELIVERED;
    }

    public function isCancelled(): bool
    {
        return $this->order_status
            === self::STATUS_CANCELLED;
    }

    public function canCancel(): bool
    {
        return in_array(
            $this->order_status,
            [
                self::STATUS_PENDING,
                self::STATUS_CONFIRMED
            ]
        );
    }
}
