<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class CertificationContent extends Model
{
    use HasUuid;

    protected $fillable = [
        'section',
        'title',
        'subtitle',
        'description',
        'badge',
        'button_text',
        'button_url',
        'image_path',
        'list_items',
        'background',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'list_items' => 'array',
        'is_active' => 'boolean',
    ];
}
