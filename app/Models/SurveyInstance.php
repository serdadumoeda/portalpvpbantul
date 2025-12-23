<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyInstance extends Model
{
    use HasUuid;

    protected $fillable = [
        'survey_id',
        'course_class_id',
        'instructor_id',
        'status',
        'opens_at',
        'closes_at',
        'triggered_at',
        'min_responses_threshold',
        'created_by',
    ];

    protected $casts = [
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
        'triggered_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            'draft' => 'Draft',
            'open' => 'Terbuka',
            'closed' => 'Ditutup',
        ];
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function responsesCount(): int
    {
        return $this->responses()->count();
    }

    public function averageNumeric(): ?float
    {
        return SurveyAnswer::whereHas('response', function ($q) {
                $q->where('survey_instance_id', $this->id);
            })
            ->avg('answer_numeric');
    }
}
