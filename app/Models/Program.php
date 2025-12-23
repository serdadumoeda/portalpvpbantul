<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasUuid;

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'kode_unit_kompetensi',
        'fasilitas_keunggulan',
        'info_tambahan',
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
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
