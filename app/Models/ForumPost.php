<?php

namespace App\Models;

use App\Models\ForumTopic;
use App\Models\User;
use App\Traits\HasReactions;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForumPost extends Model
{
    use HasUuid;
    use HasReactions;

    protected $fillable = [
        'forum_topic_id',
        'user_id',
        'content',
        'is_approved',
        'approved_by',
        'approved_at',
        'moderation_note',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_approved', false);
    }
}
