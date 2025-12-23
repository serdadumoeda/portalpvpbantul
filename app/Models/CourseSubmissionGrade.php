<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSubmissionGrade extends Model
{
    use HasUuid;

    protected $fillable = [
        'course_submission_id',
        'graded_by',
        'total_score',
        'scores',
        'rubric_meta',
        'feedback',
        'version',
        'graded_at',
    ];

    protected $casts = [
        'scores' => 'array',
        'rubric_meta' => 'array',
        'graded_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(CourseSubmission::class, 'course_submission_id');
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
