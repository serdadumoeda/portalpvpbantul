<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class PpidSetting extends Model
{
    use HasUuid;

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_button_text',
        'hero_button_link',
        'hero_image',
        'profile_title',
        'profile_description',
        'form_title',
        'form_description',
        'form_embed',
    ];
}
