<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function shippingAddresses()
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function carts()
{
    return $this->hasMany(CustomerCart::class);
}
}
