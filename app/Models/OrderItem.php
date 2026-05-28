<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',

        'product_id',
        'product_variant_id',

        'product_name',
        'product_slug',
        'product_sku',
        'product_image',

        'variant_name',
        'color_name',
        'size_name',

        'original_price',
        'final_price',

        'quantity',
        'total',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (OPTIONAL)
    |--------------------------------------------------------------------------
    */

    public function getDisplayPriceAttribute()
    {
        return $this->final_price;
    }
}
