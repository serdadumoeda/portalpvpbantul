<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasUuid;

    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
    ];
}
