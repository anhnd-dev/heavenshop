<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function galleries()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_variants',
            'color_id',
            'product_id'
        )->distinct();
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
