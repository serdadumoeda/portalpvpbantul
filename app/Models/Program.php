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
    ];
}
