<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumBadge extends Model
{
    use HasUuid;

    protected $fillable = ['name', 'label', 'description'];

    public function users(): HasMany
    {
        return $this->hasMany(UserBadge::class, 'badge_id');
    }
}
