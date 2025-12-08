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
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
}
