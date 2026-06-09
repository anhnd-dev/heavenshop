<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'tags',
        'image',

        'category_id',
        'brand_id',

        'is_featured',
        'is_active',

        'view_count',
        'sold_count',

    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured'   => 'boolean',
        'is_active'     => 'boolean',
        'view_count' => 'integer',
        'sold_count' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'product_coupon');
    }

    public function orderItems()
    {
        return $this->hasMany(
            OrderItem::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getTagsArrayAttribute(): array
    {
        if (!$this->tags) {
            return [];
        }

        return array_map(
            'trim',
            explode(',', $this->tags)
        );
    }

    public function getMinPriceAttribute(): ?float
    {
        return $this->variants()->min('price');
    }

    public function getMaxPriceAttribute(): ?float
    {
        return $this->variants()->max('price');
    }

    public function getTotalStockAttribute(): int
    {
        return (int) $this->variants()->sum('stock');
    }

    public function galleriesByColor(
        int $colorId
    ) {
        return $this->galleries()
            ->where('color_id', $colorId)
            ->orderBy('sort_order');
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
