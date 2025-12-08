<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class FaqSetting extends Model
{
    use HasUuid;

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_button_text',
        'hero_button_link',
        'hero_image',
        'intro_title',
        'intro_description',
        'info_title',
        'info_description',
        'info_stat_primary_label',
        'info_stat_primary_value',
        'info_stat_secondary_label',
        'info_stat_secondary_value',
        'contact_title',
        'contact_description',
        'contact_button_text',
        'contact_button_link',
    ];
}
