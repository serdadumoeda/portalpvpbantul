<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasUuid;

    protected $fillable = [
        'external_id',
        'nama',
        'keahlian',
        'deskripsi',
        'foto',
        'linkedin',
        'whatsapp',
        'email',
        'urutan',
        'is_active',
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
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
