<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'url',
        'position',
        'sort_order',
        'is_active',
        'start_at',
        'end_at',
    ];

    const POSITIONS = [
        'home_top' => 'Home Top',
        'home_middle' => 'Home Middle',
        'home_bottom' => 'Home Bottom',
        'collection_banner' => 'Collection Banner',
        'mobile_home' => 'Mobile Home',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    public function scopeDisplay($query)
    {
        return $query
            ->where('is_active', true)

            ->where(function ($q) {
                $q->whereNull('start_at')
                    ->orWhere('start_at', '<=', now());
            })

            ->where(function ($q) {
                $q->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            });
    }
}
