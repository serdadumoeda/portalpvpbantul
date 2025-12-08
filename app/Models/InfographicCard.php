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
    ];

    protected $casts = [
        'entries' => 'array',
    ];

    public function year()
    {
        return $this->belongsTo(InfographicYear::class, 'infographic_year_id');
    }
}
