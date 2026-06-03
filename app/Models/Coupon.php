<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'quantity',
        'used_count',
        'is_unlimited',
        'limit_per_customer',
        'description',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',

        'is_unlimited' => 'boolean',
        'is_active' => 'boolean',

        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'coupon_customers')
            ->withPivot([
                'order_id',
                'used_at',
            ])
            ->withTimestamps();
    }

    public function couponCustomers()
    {
        return $this->hasMany(CouponCustomer::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'coupon_customers')
            ->withPivot(['used_at'])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isValid($orderAmount = null): bool
    {
        if (!$this->is_active) return false;

        if ($this->start_date && now()->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && now()->gt($this->end_date)) {
            return false;
        }

        if (!$this->is_unlimited && $this->used_count >= $this->quantity) {
            return false;
        }

        if ($orderAmount !== null && $this->min_order_amount) {
            if ($orderAmount < $this->min_order_amount) {
                return false;
            }
        }

        return true;
    }
}
