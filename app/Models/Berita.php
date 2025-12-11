<?php

namespace App\Models;

use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasUuid;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'author',
        'konten',
        'excerpt',
        'published_at',
        'gambar_utama',
        'status',
        'approved_by',
        'approved_at',
        'meta_title',
        'meta_description',
        'focus_keyword',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($berita) {
            $berita->slug = $berita->slug ?: Str::slug($berita->judul) . '-' . time();
            $berita->excerpt = $berita->excerpt ?: Str::limit(strip_tags($berita->konten), 180);
            $berita->published_at = $berita->published_at ?: now();
        });

        static::updating(function ($berita) {
            if (!$berita->slug) {
                $berita->slug = Str::slug($berita->judul) . '-' . $berita->id;
            }
            if (!$berita->excerpt) {
                $berita->excerpt = Str::limit(strip_tags($berita->konten), 180);
            }
        });
    }

    public static function categories(): array
    {
        return [
            'berita' => 'Berita',
            'pers_release' => 'Pers Release',
            'informasi_pelatihan' => 'Informasi Pelatihan',
            'just_relax' => 'Just Relax',
        ];
    }

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
