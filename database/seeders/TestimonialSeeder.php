<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'nama' => 'Rina Putri',
                'jabatan' => 'Alumni Pelatihan Barista',
                'pesan' => 'Instruktur sabar, materi praktikal, dan sekarang saya bekerja di coffee shop.',
                'video_url' => null,
                'urutan' => 1,
            ],
            [
                'nama' => 'Andi Pratama',
                'jabatan' => 'Peserta Desain Grafis',
                'pesan' => 'Kurikulumnya relevan, banyak praktik langsung menggunakan software industri.',
                'video_url' => null,
                'urutan' => 2,
            ],
            [
                'nama' => 'Siti Rahma',
                'jabatan' => 'Lulus Pelatihan Las',
                'pesan' => 'Fasilitas lengkap, saya siap bersaing di dunia kerja.',
                'video_url' => null,
                'urutan' => 3,
            ],
        ];

        foreach ($items as $item) {
            Testimonial::updateOrCreate(
                ['nama' => $item['nama']],
                $item + [
                    'is_active' => true,
                    'status' => 'published',
                ]
            );
        }
    }
}
