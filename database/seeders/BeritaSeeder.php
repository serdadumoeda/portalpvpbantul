<?php

namespace Database\Seeders;

use App\Models\Berita;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    public function run(): void
    {
        $categories = array_keys(Berita::categories());
        $samples = [
            'Pelatihan Vokasi Inklusif di Sekolah Lansia',
            'Launching Program Kolaborasi Pelatihan Agroforestri',
            'Informasi Pelatihan Bahasa Jepang Level N5',
            'Tips Produktif: Just Relax Samb sambil Belajar',
        ];

        foreach ($samples as $index => $judul) {
            $kategori = $categories[$index % count($categories)];
            $baseSlug = Str::slug($judul);
            $slug = $baseSlug;
            $counter = 1;

            while (Berita::where('slug', $slug)->exists()) {
                $counter++;
                $slug = $baseSlug . '-' . $counter;
            }

            Berita::create([
                'judul' => $judul,
                'kategori' => $kategori,
                'author' => 'Tim Humas',
                'konten' => '<p>Ini adalah contoh konten berita untuk judul ' . $judul . '. Silakan perbarui melalui panel admin.</p>',
                'excerpt' => 'Contoh ringkasan untuk berita ' . $judul . '.',
                'published_at' => now()->subDays($index),
                'gambar_utama' => 'https://placehold.co/800x400?text=' . urlencode($judul),
                'slug' => $slug,
            ]);
        }
    }
}
