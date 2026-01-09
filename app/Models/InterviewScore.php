<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewScore extends Model
{
    protected $fillable = [
        'interview_allocation_id',
        'score_communication',
        'score_motivation',
        'score_technical',
        'interviewer_notes',
        'final_score',
    ];

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(InterviewAllocation::class, 'interview_allocation_id');
    }
}
