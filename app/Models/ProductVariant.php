<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'color_id',
        'size_id',
        'sku',
        'price',
        'sale_price',
        'stock',
        'sold_count',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
        'stock'       => 'integer',
        'sold_count'  => 'integer',
        'is_active'   => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function galleries()
    {
        return $this->product
            ->galleries()
            ->where(
                'color_id',
                $this->color_id
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFinalPriceAttribute(): float
    {
        return $this->sale_price ?: $this->price;
    }

    public function getVariantNameAttribute(): string
    {
        $color = $this->color?->name;
        $size  = $this->size?->name;

        return collect([
            $this->product?->name,
            $color,
            $size,
        ])->filter()->implode(' - ');
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
