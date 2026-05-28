<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    protected $fillable = [
        'name',
        'code'
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    // =========================
    // SCOPES
    // =========================

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
