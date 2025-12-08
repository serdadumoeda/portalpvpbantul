<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasUuid;

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_button_text',
        'hero_button_link',
        'hero_image',
        'map_title',
        'map_description',
        'map_embed',
        'info_section_title',
        'info_section_description',
        'cta_title',
        'cta_description',
        'cta_primary_text',
        'cta_primary_link',
        'cta_secondary_text',
        'cta_secondary_link',
    ];
}
