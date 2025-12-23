<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class PublicationItem extends Model
{
    use HasUuid;

    protected $fillable = [
        'publication_category_id',
        'title',
        'subtitle',
        'description',
        'badge',
        'image',
        'button_text',
        'button_link',
        'extra',
        'urutan',
        'is_active',
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'extra' => 'array',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(PublicationCategory::class, 'publication_category_id');
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
