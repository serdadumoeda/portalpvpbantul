<?php

namespace Database\Seeders;

use App\Models\CertificationContent;
use App\Models\CertificationScheme;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'hero' => [
                'title' => 'Sertifikasi LSP Satpel PVP Bantul',
                'description' => 'Tingkatkan kepercayaan industri dengan sertifikat kompetensi resmi dari Satpel PVP Bantul. Ikuti proses asesmen yang terstruktur dan transparan.',
                'badge' => 'Layanan Sertifikasi',
                'button_text' => 'Daftar Uji Kompetensi',
                'button_url' => '#',
                'urutan' => 1,
            ],
            'intro' => [
                'title' => 'LSP Satpel PVP Bantul',
                'description' => 'Lembaga sertifikasi profesi yang memastikan kompetensi teknis peserta pelatihan sesuai standar industri nasional.',
                'badge' => 'Tentang LSP',
                'list_items' => [
                    'Asesor berpengalaman di bidang masing-masing',
                    'Fasilitas uji kompetensi modern dan lengkap',
                    'Terakreditasi BNSP dan terhubung dengan industri nasional'
                ],
                'urutan' => 2,
            ],
            'visi' => [
                'title' => 'Visi LSP',
                'description' => 'Menjadi rujukan sertifikasi kompetensi bagi SDM unggul di wilayah DIY dan sekitarnya.',
                'badge' => 'Visi',
                'urutan' => 3,
            ],
            'misi' => [
                'title' => 'Misi Kami',
                'list_items' => [
                    'Menyelenggarakan asesmen kompetensi yang objektif dan akuntabel',
                    'Membangun kemitraan dengan industri terkait kebutuhan kompetensi',
                    'Mengembangkan skema sertifikasi yang relevan dengan perkembangan teknologi'
                ],
                'badge' => 'Misi',
                'urutan' => 4,
            ],
            'tujuan' => [
                'title' => 'Tujuan LSP Satpel PVP Bantul',
                'description' => 'Memberikan pengakuan resmi terhadap keahlian dan membuka peluang kerja lebih luas bagi peserta.',
                'list_items' => [
                    'Menjamin kualitas lulusan pelatihan Satpel PVP Bantul',
                    'Mendorong peningkatan kompetensi tenaga kerja lokal',
                    'Menjadi jembatan antara peserta dan kebutuhan industri'
                ],
                'urutan' => 5,
            ],
            'highlight' => [
                'title' => 'Sudah Ikut Uji Kompetensi?',
                'description' => 'Cek hasil asesmen Anda secara daring dan pantau proses penerbitan sertifikat dengan mudah.',
                'button_text' => 'Cek Hasil Sertifikasi',
                'button_url' => '#',
                'badge' => 'Hasil Uji',
                'background' => '#0b4f6c',
                'urutan' => 6,
            ],
        ];

        foreach ($sections as $key => $data) {
            CertificationContent::updateOrCreate(['section' => $key], $data + ['is_active' => true]);
        }

        $schemes = [
            [
                'category' => 'kluster',
                'title' => 'Skema Manajer Proyek Digital',
                'description' => 'Verifikasi kompetensi Anda dalam merancang dan mengelola proyek transformasi digital.',
                'cta_text' => 'Lihat Detail',
                'cta_url' => '#',
                'urutan' => 1,
            ],
            [
                'category' => 'kluster',
                'title' => 'Skema Barista dan Patiseri',
                'description' => 'Sertifikasi untuk profesi kuliner kreatif dengan fokus pada menu pastry dan minuman modern.',
                'cta_text' => 'Lihat Detail',
                'cta_url' => '#',
                'urutan' => 2,
            ],
            [
                'category' => 'kluster',
                'title' => 'Skema Digital Marketing',
                'description' => 'Validasi kemampuan strategi pemasaran digital, konten, dan analitik data.',
                'cta_text' => 'Lihat Detail',
                'cta_url' => '#',
                'urutan' => 3,
            ],
            [
                'category' => 'okupasi',
                'title' => 'Skema Pekerja Las SMAW 3G',
                'description' => 'Pengakuan untuk juru las profesional dengan standar industri manufaktur.',
                'cta_text' => 'Daftar',
                'cta_url' => '#',
                'urutan' => 1,
            ],
            [
                'category' => 'okupasi',
                'title' => 'Skema Operator Komputer Muda',
                'description' => 'Pastikan keterampilan pengolahan data dan aplikasi perkantoran Anda diakui.',
                'cta_text' => 'Daftar',
                'cta_url' => '#',
                'urutan' => 2,
            ],
            [
                'category' => 'okupasi',
                'title' => 'Skema Desainer Grafis',
                'description' => 'Buktikan portofolio dan pemahaman Anda tentang desain visual profesional.',
                'cta_text' => 'Daftar',
                'cta_url' => '#',
                'urutan' => 3,
            ],
        ];

        foreach ($schemes as $scheme) {
            CertificationScheme::updateOrCreate(
                ['title' => $scheme['title'], 'category' => $scheme['category']],
                $scheme + ['is_active' => true]
            );
        }
    }
}
