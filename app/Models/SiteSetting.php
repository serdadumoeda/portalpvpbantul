<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasUuid;

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = false;
}
