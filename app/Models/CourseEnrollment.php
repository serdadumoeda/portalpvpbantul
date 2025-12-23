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
        'created_by',
        'muted_until',
        'completed_at',
        'certificate_url',
    ];

    protected $casts = [
        'muted_until' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public static function statuses(): array
    {
        return [
            'active' => 'Aktif',
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
}
