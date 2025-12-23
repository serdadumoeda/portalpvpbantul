<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseSubmission extends Model
{
    use HasUuid;

    protected $fillable = [
        'course_assignment_id',
        'user_id',
        'content_text',
        'file_url',
        'link_url',
        'version',
        'late',
        'late_minutes',
        'status',
        'submitted_at',
        'graded_at',
        'total_score',
        'graded_by',
        'scores',
        'feedback',
        'quiz_answers',
        'quiz_score',
    ];

    protected $casts = [
        'late' => 'boolean',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'scores' => 'array',
        'quiz_answers' => 'array',
    ];

    public static function statuses(): array
    {
        return [
            'submitted' => 'Terkirim',
            'graded' => 'Sudah Dinilai',
            'reopened' => 'Dibuka Ulang',
        ];
    }

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(CourseAssignment::class, 'course_assignment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(CourseSubmissionGrade::class, 'course_submission_id')->orderByDesc('graded_at')->orderByDesc('created_at');
    }
}
