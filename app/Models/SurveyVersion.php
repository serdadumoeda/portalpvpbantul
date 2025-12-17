<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyVersion extends Model
{
    use HasUuid;

    protected $fillable = [
        'survey_id',
        'user_id',
        'snapshot',
        'note',
    ];

    protected $casts = [
        'snapshot' => 'array',
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
