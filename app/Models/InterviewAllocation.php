<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InterviewAllocation extends Model
{
    use HasUuid;

    protected $fillable = [
        'interview_session_id',
        'course_enrollment_id',
        'status',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(InterviewSession::class, 'interview_session_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(CourseEnrollment::class, 'course_enrollment_id');
    }

    public function score(): HasOne
    {
        return $this->hasOne(InterviewScore::class, 'interview_allocation_id');
    }
}
