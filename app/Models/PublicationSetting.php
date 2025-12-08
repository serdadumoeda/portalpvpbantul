<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class PublicationSetting extends Model
{
    use HasUuid;

    protected $fillable = [
        'hero_title',
        'hero_description',
        'hero_button_text',
        'hero_button_link',
        'hero_image',
        'intro_title',
        'intro_description',
        'alumni_title',
        'alumni_description',
        'alumni_video_url',
        'downloads_title',
        'downloads_description',
    ];
}
