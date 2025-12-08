<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasUuid;

    protected $fillable = [
        'nama',
        'keahlian',
        'deskripsi',
        'foto',
        'linkedin',
        'whatsapp',
        'email',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
