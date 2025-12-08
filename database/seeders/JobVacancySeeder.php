<?php

namespace Database\Seeders;

use App\Models\JobVacancy;
use Illuminate\Database\Seeder;

class JobVacancySeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'judul' => 'Instruktur Welding Industri',
                'perusahaan' => 'PT Baja Perkasa',
                'lokasi' => 'Bantul, DIY',
                'tipe_pekerjaan' => 'Full Time',
                'deskripsi' => 'Membimbing peserta pelatihan internal dan memastikan standar welding industri terpenuhi.',
                'kualifikasi' => '- Sertifikat kompetensi welding\n- Pengalaman 2 tahun',
                'deadline' => now()->addWeeks(2),
                'link_pendaftaran' => 'https://example.com/lowongan/welding',
                'gambar' => 'https://placehold.co/480x240?text=Lowongan+1',
            ],
            [
                'judul' => 'Digital Marketing Officer',
                'perusahaan' => 'Startup Kreatif Jogja',
                'lokasi' => 'Yogyakarta',
                'tipe_pekerjaan' => 'Remote',
                'deskripsi' => 'Mengelola kampanye pemasaran digital dan konten media sosial.',
                'kualifikasi' => '- Memahami analitik digital\n- Kreatif dan adaptif',
                'deadline' => now()->addWeeks(3),
                'link_pendaftaran' => 'https://example.com/lowongan/dm',
                'gambar' => 'https://placehold.co/480x240?text=Lowongan+2',
            ],
        ];

        foreach ($samples as $sample) {
            JobVacancy::create($sample);
        }
    }
}
