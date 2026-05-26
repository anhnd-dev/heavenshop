<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_PRODUCT = 'product';
    const TYPE_BLOG = 'blog';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'type',
        'parent_id',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Parent Category
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'parent_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Children Categories
    |--------------------------------------------------------------------------
    */

    public function children(): HasMany
    {
        return $this->hasMany(
            Category::class,
            'parent_id'
        )
            ->where('is_active', true)
            ->orderBy('name');
    }

    /*
    |--------------------------------------------------------------------------
    | Recursive Children
    |--------------------------------------------------------------------------
    */

    public function childrenRecursive(): HasMany
    {
        return $this->children()
            ->with('childrenRecursive');
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Children
    |--------------------------------------------------------------------------
    */

    public function getAllChildrenCount()
    {
        return $this->children->count() +
            $this->children->sum(fn($child) => $child->getAllChildrenCount());
    }

    public function getAllChildrenIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {

            $ids = array_merge(
                $ids,
                $child->getAllChildrenIds()
            );
        }

        return $ids;
    }

    public function getBreadcrumbAttribute()
    {
        $breadcrumbs = [];

        $category = $this;

        while ($category) {

            array_unshift($breadcrumbs, $category);

            $category = $category->parent;
        }

        return $breadcrumbs;
    }

    /*
    |--------------------------------------------------------------------------
    | Blogs
    |--------------------------------------------------------------------------
    */

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Products
    |--------------------------------------------------------------------------
    */

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
}
