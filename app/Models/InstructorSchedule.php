<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class InstructorSchedule extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'meta',
        'days',
        'rows',
        'unit_descriptions',
        'trainer',
        'signatures',
        'created_by',
    ];

    protected $casts = [
        'meta' => 'array',
        'days' => 'array',
        'rows' => 'array',
        'unit_descriptions' => 'array',
        'trainer' => 'array',
        'signatures' => 'array',
    ];
}
