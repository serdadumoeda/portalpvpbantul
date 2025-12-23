<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyResponse extends Model
{
    use HasUuid;

    protected $fillable = [
        'survey_id',
        'user_id',
        'course_class_id',
        'instructor_id',
        'survey_instance_id',
        'session_id',
        'ip_address',
        'user_agent',
        'submitted_at',
        'meta',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'meta' => 'array',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function instance(): BelongsTo
    {
        return $this->belongsTo(SurveyInstance::class, 'survey_instance_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
