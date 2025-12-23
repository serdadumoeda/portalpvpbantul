<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class InfographicYear extends Model
{
    use HasUuid;

    protected $fillable = [
        'tahun',
        'title',
        'headline',
        'description',
        'hero_image',
        'hero_button_text',
        'hero_button_link',
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

    public function metrics()
    {
        return $this->hasMany(InfographicMetric::class)->orderBy('urutan');
    }

    public function cards()
    {
        return $this->hasMany(InfographicCard::class)->orderBy('urutan');
    }

    public function embeds()
    {
        return $this->hasMany(InfographicEmbed::class)->orderBy('urutan');
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
