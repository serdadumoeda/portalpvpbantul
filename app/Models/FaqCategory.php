<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'subtitle',
        'icon',
        'urutan',
        'is_active',
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(FaqItem::class)->orderBy('urutan');
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
