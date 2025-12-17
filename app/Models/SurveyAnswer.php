<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyAnswer extends Model
{
    use HasUuid;

    protected $fillable = [
        'survey_response_id',
        'survey_question_id',
        'answer_text',
        'selected_option_ids',
        'answer_numeric',
    ];

    protected $casts = [
        'selected_option_ids' => 'array',
        'answer_numeric' => 'float',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'survey_response_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }
}
