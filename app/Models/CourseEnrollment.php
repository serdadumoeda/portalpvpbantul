<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEnrollment extends Model
{
    use HasUuid;

    protected $fillable = [
        'course_class_id',
        'user_id',
        'status',
        'admin_status',
        'admin_note',
        'written_score',
        'interview_score',
        'final_score',
        'created_by',
        'muted_until',
        'completed_at',
        'certificate_url',
    ];

    protected $casts = [
        'muted_until' => 'datetime',
        'completed_at' => 'datetime',
        'written_score' => 'float',
        'interview_score' => 'float',
        'final_score' => 'float',
    ];

    public static function statuses(): array
    {
        return [
            'active' => 'Aktif',
            'approved' => 'Disetujui',
            'pending' => 'Pending',
            'rejected' => 'Ditolak',
            'blocked' => 'Diblokir',
            'completed' => 'Selesai',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(CourseClass::class, 'course_class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updateFinalScore(): void
    {
        $written = $this->written_score;
        $interview = $this->interview_score;

        if ($written === null && $interview === null) {
            return;
        }

        $writtenPart = $written !== null ? $written * 0.4 : 0;
        $interviewPart = $interview !== null ? $interview * 0.6 : 0;
        $this->final_score = round($writtenPart + $interviewPart, 2);
        $this->save();
    }
}
