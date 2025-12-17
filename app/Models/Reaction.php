<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reaction extends Model
{
    use HasUuid;

    protected $fillable = [
        'user_id',
        'reactionable_id',
        'reactionable_type',
        'type',
    ];

    protected $table = 'forum_reactions';

    public function reactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
