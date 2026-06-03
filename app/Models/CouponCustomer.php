<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponCustomer extends Model
{
    protected $fillable = [
        'coupon_id',
        'customer_id',
        'order_id',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function scopeAvailable($q)
    {
        return $q->whereNull('used_at')
            ->whereHas('coupon', function ($q) {
                $q->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('start_date')
                            ->orWhere('start_date', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                            ->orWhere('end_date', '>', now());
                    });
            });
    }

    public function scopeUsed($q)
    {
        return $q->whereNotNull('used_at');
    }

    public function scopeExpired($q)
    {
        return $q->whereNull('used_at')
            ->whereHas('coupon', function ($q) {
                $q->whereNotNull('end_date')
                    ->where('end_date', '<=', now());
            });
    }

    public function scopeOfCustomer($q, $customerId)
    {
        return $q->where('customer_id', $customerId);
    }
}
