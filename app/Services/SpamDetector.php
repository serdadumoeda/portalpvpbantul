<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SpamDetector
{
    public static function detect(string $text): void
    {
        $clean = Str::lower(trim($text));
        $config = config('forum');

        foreach ($config['spam_keywords'] as $keyword) {
            if (Str::contains($clean, $keyword)) {
                self::fail();
            }
        }

        if (preg_match_all('/https?:\/\/|www\./i', $text) > ($config['max_links'] ?? 1)) {
            self::fail('Konten tidak boleh memuat terlalu banyak tautan.');
        }

        if (preg_match('/(.)\\1{' . ($config['max_repeated_characters'] ?? 6) . ',}/', $text)) {
            self::fail('Kalimat mengandung pola karakter yang mencurigakan.');
        }
    }

    protected static function fail(string $message = 'Konten terdeteksi sebagai spam.'): void
    {
        throw ValidationException::withMessages(['content' => $message]);
    }
}
