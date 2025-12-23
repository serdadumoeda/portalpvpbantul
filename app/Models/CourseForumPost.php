<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseForumPost extends Model
{
    use HasUuid;

    protected $fillable = [
        'course_forum_topic_id',
        'user_id',
        'body',
    ];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(CourseForumTopic::class, 'course_forum_topic_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(CourseForumReport::class, 'course_forum_post_id');
    }
}
