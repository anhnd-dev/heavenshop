<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    use HasFactory;

    protected $table = 'wards';

    protected $fillable = [
        'name',
        'code',
        'district_id'
    ];

    protected $casts = [
        'district_id' => 'integer',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    // =========================
    // SCOPES
    // =========================

    public function scopeByDistrict($query, int $districtId)
    {
        return $query->where('district_id', $districtId);
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
