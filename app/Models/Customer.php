<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function coupons()
    {
        return $this->belongsToMany(
            Coupon::class,
            'coupon_customers'
        );
    }

    public function addresses()
    {
        return $this->hasMany(
            CustomerAddress::class
        );
    }

    public function defaultAddress()
    {
        return $this->hasOne(
            CustomerAddress::class
        )->where('is_default', true);
    }

    public function carts()
    {
        return $this->hasMany(
            CustomerCart::class
        );
    }

    public function orders()
    {
        return $this->hasMany(
            Order::class
        );
    }
}
