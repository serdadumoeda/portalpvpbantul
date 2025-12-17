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

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
