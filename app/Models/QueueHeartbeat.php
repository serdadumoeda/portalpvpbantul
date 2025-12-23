<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class QueueHeartbeat extends Model
{
    use HasUuid;

    protected $fillable = [
        'queue',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
