<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasUuid;

    protected $fillable = [
        'nama',
        'logo',
        'tautan',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
