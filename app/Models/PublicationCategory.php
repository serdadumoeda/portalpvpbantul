<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class PublicationCategory extends Model
{
    use HasUuid;

    protected $fillable = [
        'name',
        'slug',
        'layout',
        'subtitle',
        'description',
        'columns',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(PublicationItem::class)->where('is_active', true)->orderBy('urutan');
    }
}
