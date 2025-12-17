<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponseDraft extends Model
{
    use HasUuid;

    protected $fillable = [
        'survey_id',
        'user_id',
        'session_id',
        'data',
        'saved_at',
    ];

    protected $casts = [
        'data' => 'array',
        'saved_at' => 'datetime',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
