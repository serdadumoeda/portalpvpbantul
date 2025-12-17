<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyQuestionOption extends Model
{
    use HasUuid;

    protected $fillable = [
        'survey_question_id',
        'label',
        'value',
        'position',
        'is_other',
    ];

    protected $casts = [
        'is_other' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(SurveyQuestion::class, 'survey_question_id');
    }
}
