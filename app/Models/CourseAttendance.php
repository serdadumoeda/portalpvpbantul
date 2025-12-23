<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseAttendance extends Model
{
    use HasUuid;

    protected $fillable = [
        'course_session_id',
        'user_id',
        'status',
        'reason',
        'proof_url',
        'checked_at',
        'created_by',
        'recorded_by',
        'recorded_source',
        'meta',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'meta' => 'array',
    ];

    public static function statuses(): array
    {
        return [
            'hadir' => 'Hadir',
            'telat' => 'Telat',
            'izin' => 'Izin',
            'absen' => 'Absen',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CourseSession::class, 'course_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
