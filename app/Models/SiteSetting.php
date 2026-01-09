<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasUuid;

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = false;

    public static function valueOf(string $key, mixed $default = null): mixed
    {
        $cacheKey = "site_setting:{$key}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting?->value ?? $default;
        });
    }
}
