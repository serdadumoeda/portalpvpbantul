<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasUuid;

    protected $fillable = [
        'judul',
        'perusahaan',
        'lokasi',
        'tipe_pekerjaan',
        'deskripsi',
        'kualifikasi',
        'deadline',
        'link_pendaftaran',
        'gambar',
        'is_active',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
    ];
}
