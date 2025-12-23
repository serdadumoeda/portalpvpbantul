<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class PublicServiceFlow extends Model
{
    use HasUuid;

    protected $fillable = [
        'category',
        'title',
        'subtitle',
        'image',
        'steps',
        'urutan',
        'is_active',
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function getStepsListAttribute(): array
    {
        if (!$this->steps) {
            return [];
        }

        $steps = json_decode($this->steps, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($steps)) {
            return $steps;
        }

        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->steps))));
    }

    public function setStepsAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['steps'] = json_encode($value);
            return;
        }

        $this->attributes['steps'] = $value;
    }

    public static function statuses(): array
    {
        return [
            'draft' => 'Draft',
            'pending' => 'Menunggu Review',
            'published' => 'Terpublikasi',
        ];
    }
}
