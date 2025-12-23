<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Models\CourseAnnouncement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseClass extends Model
{
    use HasUuid;

    protected $fillable = [
        'title',
        'description',
        'format',
        'prerequisites',
        'competencies',
        'badge',
        'is_active',
        'status',
        'instructor_id',
        'created_by',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'prerequisites' => 'array',
        'competencies' => 'array',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            'draft' => 'Draft',
            'pending' => 'Menunggu Review',
            'published' => 'Terpublikasi',
        ];
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CourseSession::class)->orderBy('start_at');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CourseAssignment::class)->orderBy('due_at');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(CourseAnnouncement::class, 'course_class_id')->latest();
    }
}
