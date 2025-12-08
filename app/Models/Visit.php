<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasUuid;

    protected $fillable = [
        'path',
        'ip_address',
        'user_agent',
        'referer',
    ];
}
