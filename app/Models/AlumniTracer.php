<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniTracer extends Model
{
    use HasUuid;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'national_id',
        'alumni_number',
        'program_id',
        'program_name',
        'graduation_year',
        'training_batch',
        'status',
        'job_title',
        'company_name',
        'industry_sector',
        'job_start_date',
        'employment_type',
        'salary_range',
        'continue_study',
        'is_entrepreneur',
        'business_name',
        'business_sector',
        'satisfaction_rating',
        'feedback',
        'platform_origin',
        'consent_given',
        'consent_at',
        'user_id',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'continue_study' => 'boolean',
        'is_entrepreneur' => 'boolean',
        'consent_given' => 'boolean',
        'consent_at' => 'datetime',
        'is_verified' => 'boolean',
        'job_start_date' => 'date',
        'verified_at' => 'datetime',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
