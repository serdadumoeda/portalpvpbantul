<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseSession extends Model
{
    use HasUuid;

    protected $fillable = [
        'course_class_id',
        'title',
        'description',
        'start_at',
        'end_at',
        'meeting_link',
        'recording_url',
        'recording_expired_at',
        'allow_download',
        'attendance_code',
        'attendance_code_expires_at',
        'is_active',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
        'published_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'recording_expired_at' => 'datetime',
        'allow_download' => 'boolean',
        'attendance_code_expires_at' => 'datetime',
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

    public function course(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(CourseAttendance::class, 'course_session_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
