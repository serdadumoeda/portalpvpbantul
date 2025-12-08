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
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(FaqItem::class)->orderBy('urutan');
    }
}
