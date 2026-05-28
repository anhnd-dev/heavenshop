<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    protected $fillable = [
        'name',
        'code',
        'province_id'
    ];

    protected $casts = [
        'province_id' => 'integer',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }

    // =========================
    // SCOPES
    // =========================

    public function scopeByProvince($query, int $provinceId)
    {
        return $query->where('province_id', $provinceId);
    }

    public function scopeDropdown($query)
    {
        return $query
            ->select('id', 'name')
            ->orderBy('name');
    }

    // =========================
    // ACCESSORS
    // =========================

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }
}
