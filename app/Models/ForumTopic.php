<?php

namespace App\Models;

use App\Models\ForumPost;
use App\Models\User;
use App\Traits\HasReactions;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumTopic extends Model
{
    use HasUuid;
    use HasReactions;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'user_id',
        'is_locked',
        'is_pinned',
        'is_approved',
        'approved_by',
        'approved_at',
        'moderation_note',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'is_pinned' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(ForumPost::class)->orderBy('created_at');
    }

    public function approvedPosts(): HasMany
    {
        return $this->hasMany(ForumPost::class)->approved()->orderBy('created_at');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->approved()
            ->withCount('posts')
            ->orderByDesc('reaction_count')
            ->orderByDesc('posts_count');
    }
}
