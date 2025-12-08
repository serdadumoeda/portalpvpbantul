<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class ContactChannel extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'subtitle',
        'icon',
        'link',
        'label',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
