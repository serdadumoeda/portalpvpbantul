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
    ];

    protected $casts = [
        'extra' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(PublicationCategory::class, 'publication_category_id');
    }
}
