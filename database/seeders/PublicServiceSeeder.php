<?php

namespace Database\Seeders;

use App\Models\PublicServiceFlow;
use App\Models\PublicServiceSetting;
use Illuminate\Database\Seeder;

class PublicServiceSeeder extends Seeder
{
    public function run(): void
    {
        $setting = PublicServiceSetting::first() ?? new PublicServiceSetting();
        $setting->fill([
            'hero_title' => 'Pelayanan Publik',
            'hero_subtitle' => 'Pelayanan responsif & humanis',
            'hero_description' => 'Tim pelayanan kami siap membantu masyarakat mendapatkan layanan pelatihan, sertifikasi, konsultasi produktivitas, dan kanal pengaduan resmi.',
            'hero_button_text' => 'Unduh Maklumat',
            'hero_button_link' => '#maklumat',
            'intro_title' => 'Pelayanan Publik',
            'intro_description' => 'Pelayanan publik di Satpel PVP Bantul disiapkan dengan standar mutu yang terukur.',
            'intro_content' => '<p>Setiap unit layanan dibangun untuk memberikan pengalaman terbaik bagi masyarakat, mulai dari layanan pendaftaran pelatihan, sertifikasi, hingga kunjungan instansi. SDM kami dilatih untuk memastikan respon cepat dan ramah, serta memanfaatkan sistem digital untuk memudahkan dokumentasi.</p><p>Melalui sinergi dengan pemerintah daerah dan industri, layanan kami terus berinovasi sehingga mampu menjawab kebutuhan kompetensi kerja terkini.</p>',
            'regulation_title' => 'Dasar Hukum Pelayanan',
            'regulation_items' => [
                'Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan Publik.',
                'Peraturan Pemerintah No. 96 Tahun 2012 tentang Pelaksanaan UU Pelayanan Publik.',
                'Permenaker No. 8 Tahun 2022 tentang Organisasi dan Tata Kerja BPVP.',
            ],
            'policy_title' => 'Maklumat Pelayanan',
            'policy_subtitle' => 'Dengan ini kami menyatakan:',
            'policy_description' => "1. Sangat siap menyelenggarakan pelayanan sesuai standar yang ditetapkan.\n2. Menyediakan informasi layanan secara jujur, cepat, dan ramah.\n3. Apabila tidak menepati janji layanan, kami siap menerima sanksi sesuai ketentuan.",
            'policy_signature' => 'Kepala Satpel PVP Bantul',
            'policy_position' => 'Pejabat Penanggung Jawab Pelayanan',
            'standard_title' => 'Standar Pelayanan Publik',
            'standard_description' => 'Standar disusun berdasarkan analisis kebutuhan masyarakat dan benchmarking pelayanan terbaik.',
            'standard_document_title' => 'Standar Pelayanan Publik Satpel PVP Bantul',
            'standard_document_description' => 'Dokumen berisi jenis layanan, waktu penyelesaian, indikator mutu, dan kanal pengaduan resmi.',
            'standard_document_badge' => 'Dokumen Resmi',
            'flow_section_title' => 'Alur Pelayanan & Pengaduan',
            'flow_section_description' => 'Ikuti setiap langkah berikut untuk memastikan proses layanan berlangsung singkat dan transparan.',
            'cta_title' => 'Anda Siap Tingkatkan Skill dengan Kami?',
            'cta_description' => 'Hubungi petugas layanan kami untuk konsultasi kebutuhan pelatihan atau penguatan produktivitas.',
            'cta_primary_text' => 'Cek Jadwal Pelatihan',
            'cta_primary_link' => '/pelatihan/jadwal',
            'cta_secondary_text' => 'Hubungi Kami',
            'cta_secondary_link' => '/kontak',
        ])->save();

        $flows = [
            [
                'category' => 'pelatihan',
                'title' => 'Alur Pelayanan Pelatihan Institusional',
                'subtitle' => 'Panduan mengikuti pelatihan reguler di Satpel PVP Bantul.',
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=900&q=70',
                'steps' => [
                    'Melengkapi formulir pendaftaran dan berkas administrasi.',
                    'Verifikasi kelengkapan oleh petugas layanan dan penentuan jadwal.',
                    'Mengikuti pembekalan awal dan pengarahan instruktur.',
                    'Pelaksanaan pelatihan sesuai jadwal dan modul kompetensi.',
                    'Evaluasi akhir serta penerbitan sertifikat kehadiran.',
                ],
                'urutan' => 1,
            ],
            [
                'category' => 'pelatihan',
                'title' => 'Alur Pelayanan Mobile Training Unit (MTU)',
                'subtitle' => 'Layanan pelatihan bergerak untuk menjangkau wilayah komunitas.',
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=70',
                'steps' => [
                    'Pengajuan permohonan MTU dari pemerintah daerah/komunitas.',
                    'Survei lokasi dan ketersediaan peserta minimal.',
                    'Penetapan jadwal dan instruktur lapangan.',
                    'Pelaksanaan pelatihan beserta monitoring evaluasi.',
                    'Penyerahan laporan hasil pelatihan kepada pengusul.',
                ],
                'urutan' => 2,
            ],
            [
                'category' => 'sertifikasi',
                'title' => 'Alur Pelayanan Uji Kompetensi',
                'subtitle' => 'Skema uji kompetensi untuk memastikan standar profesi.',
                'image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=900&q=70',
                'steps' => [
                    'Pendaftaran peserta uji dengan memilih skema yang tersedia.',
                    'Verifikasi dokumen pengalaman/pelatihan dan pembayaran PNBP.',
                    'Pelaksanaan asesmen oleh asesor kompetensi.',
                    'Rapat pleno asesor dan penerbitan sertifikat kompetensi.',
                ],
                'urutan' => 3,
            ],
            [
                'category' => 'pengaduan',
                'title' => 'Alur Pelayanan Pengaduan',
                'subtitle' => 'Kanal pengaduan disiapkan untuk menerima masukan masyarakat.',
                'image' => 'https://images.unsplash.com/photo-1525182008055-f88b95ff7980?auto=format&fit=crop&w=900&q=70',
                'steps' => [
                    'Penyampaian pengaduan melalui formulir online, email, atau tatap muka.',
                    'Verifikasi dan penomoran tiket pengaduan oleh petugas layanan.',
                    'Koordinasi dengan unit terkait untuk klarifikasi.',
                    'Penyampaian jawaban/resolusi kepada pelapor maksimal 5 hari kerja.',
                    'Evaluasi internal untuk perbaikan layanan berkelanjutan.',
                ],
                'urutan' => 4,
            ],
        ];

        foreach ($flows as $flowData) {
            PublicServiceFlow::updateOrCreate(
                ['title' => $flowData['title']],
                $flowData + ['is_active' => true]
            );
        }
    }
}
