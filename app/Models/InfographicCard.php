<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class InfographicCard extends Model
{
    use HasUuid;

    protected $fillable = [
        'infographic_year_id',
        'title',
        'entries',
        'urutan',
        'status',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'entries' => 'array',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function year()
    {
        return $this->belongsTo(InfographicYear::class, 'infographic_year_id');
    }

    public static function statuses(): array
    {
        return [
            'draft' => 'Draft',
            'pending' => 'Menunggu Review',
            'published' => 'Terpublikasi',
        ];
    }
}
