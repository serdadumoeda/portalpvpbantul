<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasUuid;

    public $incrementing = false;
    protected $table = 'alumni';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'field_of_study',
        'graduation_year',
        'employment_status',
        'notes',
        'is_active',
    ];
}
