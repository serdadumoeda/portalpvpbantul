<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyQuestion extends Model
{
    use HasUuid;

    protected $fillable = [
        'survey_id',
        'survey_section_id',
        'type',
        'question',
        'description',
        'is_required',
        'position',
        'settings',
        'placeholder',
        'validation',
        'visibility_rules',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'settings' => 'array',
        'validation' => 'array',
        'visibility_rules' => 'array',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(SurveySection::class, 'survey_section_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(SurveyQuestionOption::class)->orderBy('position');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class, 'survey_question_id');
    }

    public function scopeVisibleForAnswers($query, array $answers)
    {
        return $query->filter(function (self $question) use ($answers) {
            $rules = $question->visibility_rules ?? [];
            if (! $rules) return true;
            foreach ($rules as $rule) {
                $targetValue = $answers[$rule['question_id']] ?? null;
                if (isset($rule['equals']) && $targetValue == $rule['equals']) {
                    return $rule['action'] === 'show';
                }
                if (isset($rule['in']) && is_array($targetValue) && array_intersect($targetValue, $rule['in'])) {
                    return $rule['action'] === 'show';
                }
            }
            return true;
        });
    }
}
