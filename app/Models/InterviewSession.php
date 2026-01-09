<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterviewSession extends Model
{
    use HasUuid;

    protected $fillable = [
        'training_schedule_id',
        'interviewer_id',
        'date',
        'start_time',
        'end_time',
        'location',
        'quota',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function trainingSchedule(): BelongsTo
    {
        return $this->belongsTo(TrainingSchedule::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(InterviewAllocation::class);
    }
}
