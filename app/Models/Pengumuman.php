<?php

namespace App\Models;

use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengumuman extends Model
{
    use HasUuid;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'judul',
        'slug',
        'isi',
        'file_download',
        'status',
        'approved_by',
        'approved_at',
        'meta_title',
        'meta_description',
        'focus_keyword',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_PUBLISHED => 'Terbit',
        ];
    }
}
