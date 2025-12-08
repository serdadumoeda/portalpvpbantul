<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class OrgStructure extends Model
{
    use HasUuid;

    protected $fillable = [
        'nama',
        'jabatan',
        'parent_id',
        'urutan',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('urutan');
    }
}
