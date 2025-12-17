<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    use HasUuid;

    public $timestamps = false;

    protected $fillable = ['user_id', 'badge_id', 'awarded_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(ForumBadge::class, 'badge_id');
    }
}
