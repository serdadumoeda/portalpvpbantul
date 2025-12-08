<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class TrainingService extends Model
{
    use HasUuid;

    protected $fillable = [
        'judul',
        'deskripsi',
        'fasilitas',
        'gambar',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
