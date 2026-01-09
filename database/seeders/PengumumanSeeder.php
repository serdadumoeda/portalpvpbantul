<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        if (Pengumuman::count() > 0) {
            return;
        }

        $items = [
            [
                'judul' => 'Pembukaan Pendaftaran Pelatihan Gelombang Baru',
                'isi' => '<p>Pendaftaran pelatihan vokasi gelombang berikutnya telah dibuka. Segera daftarkan diri Anda melalui kanal resmi Satpel PVP Bantul.</p>',
                'status' => Pengumuman::STATUS_PUBLISHED,
                'published_at' => now()->subDays(3),
            ],
            [
                'judul' => 'Perubahan Jadwal Kelas Sertifikasi',
                'isi' => '<p>Terdapat penyesuaian jadwal untuk kelas sertifikasi minggu ini. Cek jadwal terbaru di halaman Sertifikasi atau hubungi admin.</p>',
                'status' => Pengumuman::STATUS_PUBLISHED,
                'published_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Pemeliharaan Sistem',
                'isi' => '<p>Sistem akan menjalani pemeliharaan terjadwal pada akhir pekan. Beberapa fitur mungkin tidak tersedia sementara.</p>',
                'status' => Pengumuman::STATUS_DRAFT,
                'published_at' => now()->addDays(1),
            ],
        ];

        $hasPublishedAt = Schema::hasColumn('pengumumen', 'published_at');

        foreach ($items as $item) {
            if (! $hasPublishedAt) {
                unset($item['published_at']);
            }
            Pengumuman::create(array_merge($item, [
                'slug' => Str::slug($item['judul']) . '-' . Str::random(4),
            ]));
        }
    }
}
