<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Frontend extends Model
{
    use SoftDeletes;

    public const SETTING = 'setting.data';
    public const COOKIE = 'cookie.data';
    public const LOGO_ICON = 'logo_icon.data';
    public const SEO = 'seo.data';
    public const CONTACT = 'contact_us.content';
    public const POLICY = 'policy.element';

    protected $table = 'frontends';

    protected $fillable = [
        'data_key',
        'data_value',
        'is_active',
    ];

    protected $casts = [
        'data_value' => 'array',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public static function getData(string $key): array
    {
        return static::query()
            ->where('data_key', $key)
            ->value('data_value') ?? [];
    }

    public static function getSetting(
        string $key,
        mixed $default = null
    ): mixed {

        return data_get(
            static::getData(static::SETTING),
            $key,
            $default
        );
    }

    public static function setData(
        string $key,
        array $data
    ): self {
        return static::updateOrCreate(
            ['data_key' => $key],
            [
                'data_value' => $data,
                'is_active' => true,
            ]
        );
    }
}
