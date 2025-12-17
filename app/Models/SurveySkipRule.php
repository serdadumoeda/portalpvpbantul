<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveySkipRule extends Model
{
    use HasUuid;

    protected $fillable = [
        'survey_id',
        'survey_question_id',
        'target_section_id',
        'conditions',
    ];

    protected $casts = [
        'conditions' => 'array',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }

    public function targetSection(): BelongsTo
    {
        return $this->belongsTo(SurveySection::class, 'target_section_id');
    }
}
