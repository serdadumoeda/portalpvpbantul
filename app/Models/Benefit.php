<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    use HasUuid;

    protected $fillable = [
        'judul',
        'deskripsi',
        'ikon',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
