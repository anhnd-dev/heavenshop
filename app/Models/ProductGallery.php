<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductGallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_galleries';

    protected $fillable = [
        'product_id',
        'color_id',
        'file',
        'type',
        'thumbnail',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // =========================
    // RELATIONS
    // =========================

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    // =========================
    // ACCESSORS
    // =========================

    public function getIsImageAttribute(): bool
    {
        return $this->type === 'image';
    }

    public function getIsVideoAttribute(): bool
    {
        return $this->type === 'video';
    }

    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file) {
            return null;
        }

        return asset(
            'uploads/gallery/' . $this->file
        );
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }

        return asset(
            'uploads/gallery/' . $this->thumbnail
        );
    }
}
