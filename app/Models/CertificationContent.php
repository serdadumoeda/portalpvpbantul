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
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'list_items' => 'array',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            'draft' => 'Draft',
            'pending' => 'Menunggu Review',
            'published' => 'Terpublikasi',
        ];
    }
}
