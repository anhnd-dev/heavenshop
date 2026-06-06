<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCartItem extends Model
{
    protected $fillable = [

        'customer_id',

        'product_variant_id',

        'quantity',

        'selected'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function variant()
    {
        return $this->belongsTo(
            ProductVariant::class,
            'product_variant_id'
        );
    }
}
