<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class TrainingSchedule extends Model
{
    use HasUuid;

    protected $fillable = [
        'external_id',
        'batch_id',
        'judul',
        'penyelenggara',
        'lokasi',
        'mulai',
        'selesai',
        'kuota',
        'bulan',
        'tahun',
        'pendaftaran_link',
        'catatan',
        'is_active',
    ];

    protected $casts = [
        'mulai' => 'date',
        'selesai' => 'date',
        'is_active' => 'boolean',
    ];
}
