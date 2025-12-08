<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class InfographicMetric extends Model
{
    use HasUuid;

    protected $fillable = [
        'infographic_year_id',
        'label',
        'value',
        'urutan',
    ];

    public function year()
    {
        return $this->belongsTo(InfographicYear::class, 'infographic_year_id');
    }
}
