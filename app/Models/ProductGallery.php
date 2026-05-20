<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductGallery extends Model
{
    use SoftDeletes;

    protected $table = 'product_galleries';

    protected $fillable = [
        'product_id',
        'image',
    ];

    protected $casts = [
        'product_id' => 'integer',
    ];

    // =========================
    // RELATIONS
    // =========================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // =========================
    // ACCESSORS
    // =========================

    public function getImageUrlAttribute(): string
    {
        return $this->image
            ? asset('uploads/product/gallery/' . $this->image)
            : asset('admin/images/default-image.png');
    }
}
