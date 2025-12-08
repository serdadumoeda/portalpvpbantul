<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class PpidRequest extends Model
{
    use HasUuid;

    protected $fillable = [
        'nama',
        'nomor_identitas',
        'npwp',
        'pekerjaan',
        'jenis_pemohon',
        'alamat',
        'no_hp',
        'email',
        'informasi_dimohon',
        'tujuan_penggunaan',
        'cara_memperoleh',
        'tanda_tangan',
    ];
}
