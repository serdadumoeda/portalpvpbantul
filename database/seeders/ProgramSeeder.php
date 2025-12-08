<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'judul' => 'Teknik Otomotif Mobil',
                'deskripsi' => 'Pelatihan kompetensi perbaikan kendaraan ringan, sistem injeksi, dan kelistrikan body mobil sesuai standar industri.',
                'gambar' => 'https://placehold.co/800x600?text=Otomotif',
            ],
            [
                'judul' => 'Desain Grafis',
                'deskripsi' => 'Mempelajari software desain seperti Adobe Photoshop, Illustrator, dan CorelDraw untuk kebutuhan industri kreatif dan percetakan.',
                'gambar' => 'https://placehold.co/800x600?text=Desain+Grafis',
            ],
            [
                'judul' => 'Barista & F&B Service',
                'deskripsi' => 'Pelatihan meracik kopi (manual brew & espresso based) serta pelayanan hospitality standar restoran dan hotel.',
                'gambar' => 'https://placehold.co/800x600?text=Barista',
            ],
        ];

        foreach ($programs as $program) {
            Program::firstOrCreate(
                ['judul' => $program['judul']],
                $program
            );
        }
    }
}
