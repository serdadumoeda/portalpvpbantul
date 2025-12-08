<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasUuid;

    protected $fillable = [
        'nama',
        'jabatan',
        'pesan',
        'video_url',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
