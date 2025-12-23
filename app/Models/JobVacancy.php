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
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            'draft' => 'Draft',
            'pending' => 'Menunggu Review',
            'published' => 'Terpublikasi',
        ];
    }
}
