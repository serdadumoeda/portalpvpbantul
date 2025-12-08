<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $dataProfiles = [
            [
                'key' => 'profil_instansi',
                'judul' => 'Profil Satpel PVP Bantul',
                'konten' => '<p>Balai Pelatihan Vokasi dan Produktivitas (BPVP) Bantul menjadi pusat pengembangan kompetensi kerja dengan fasilitas pelatihan modern, area praktik luas, serta tenaga instruktur berpengalaman. Kami fokus membangun SDM unggul yang siap menjawab kebutuhan industri.</p>',
                'gambar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'profil_selayang',
                'judul' => 'Selayang Pandang',
                'konten' => '<p>Satpel PVP Bantul berdiri di atas lahan lebih dari 10 hektare dengan fasilitas workshop industri, asrama peserta, area publik, serta ruang kreatif. Kami melayani pelatihan multi-kejuruan mulai dari manufaktur, TIK, hingga ekonomi kreatif.</p>',
                'gambar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'profil_denah',
                'judul' => 'Denah Lokasi',
                'konten' => '<p>Denah lokasi memudahkan peserta mengenali area workshop, asrama, aula, dan fasilitas pendukung lainnya.</p>',
                'gambar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'visi_misi',
                'judul' => 'Visi & Misi',
                'konten' => '<ul><li>Visi: Menjadi Balai Unggulan.</li><li>Misi: Melatih tenaga kerja kompeten.</li></ul>',
                'gambar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sejarah',
                'judul' => 'Sejarah PVP',
                'konten' => '<p>PVP didirikan pada tahun 1980...</p>',
                'gambar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'struktur',
                'judul' => 'Struktur Organisasi',
                'konten' => 'Daftar pejabat struktural...',
                'gambar' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($dataProfiles as $data) {
            Profile::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
