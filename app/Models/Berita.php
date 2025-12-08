<?php

namespace App\Models;

use App\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasUuid;

    protected $fillable = [
        'judul',
        'slug',
        'kategori',
        'author',
        'konten',
        'excerpt',
        'published_at',
        'gambar_utama',
    ];

    protected $casts = [
        'published_at' => 'datetime',
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
}
