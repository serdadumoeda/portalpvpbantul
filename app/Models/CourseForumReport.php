<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseForumReport extends Model
{
    use HasUuid;

    protected $fillable = [
        'course_forum_post_id',
        'reporter_id',
        'reason',
        'status',
    ];

    public static function statuses(): array
    {
        return [
            'open' => 'Terbuka',
            'resolved' => 'Selesai',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(CourseForumPost::class, 'course_forum_post_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }
}
