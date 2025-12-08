<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class CertificationScheme extends Model
{
    use HasUuid;

    protected $fillable = [
        'category',
        'title',
        'subtitle',
        'description',
        'cta_text',
        'cta_url',
        'image_path',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
