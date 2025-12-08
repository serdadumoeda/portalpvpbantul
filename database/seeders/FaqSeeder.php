<?php

namespace Database\Seeders;

use App\Models\FaqCategory;
use App\Models\FaqItem;
use App\Models\FaqSetting;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $setting = FaqSetting::first() ?? new FaqSetting();
        $setting->fill([
            'hero_title' => 'Frequently Asked Questions',
            'hero_subtitle' => 'Pertanyaan Populer',
            'hero_description' => 'Panduan tuntas untuk calon peserta pelatihan, alumni, hingga mitra industri yang ingin memanfaatkan layanan Satpel PVP Bantul.',
            'hero_button_text' => 'Telusuri FAQ',
            'hero_button_link' => '#faq',
            'intro_title' => 'Jawaban cepat, pengalaman layanan yang lebih baik',
            'intro_description' => 'Kami mengelompokkan pertanyaan berdasarkan topik sehingga Anda dapat menemukan jawaban hanya dalam hitungan detik.',
            'info_title' => 'Masih bingung?',
            'info_description' => 'Silakan hubungi petugas front office kami atau kirimkan pesan melalui halaman kontak.',
            'info_stat_primary_label' => 'FAQ Teratasi',
            'info_stat_primary_value' => '250+',
            'info_stat_secondary_label' => 'Waktu Respon',
            'info_stat_secondary_value' => '< 24 Jam',
            'contact_title' => 'Kirim Pertanyaan Lainnya',
            'contact_description' => 'Tim layanan kami siap membantu melalui telepon, WhatsApp, atau email resmi Satpel PVP Bantul.',
            'contact_button_text' => 'Hubungi Petugas',
            'contact_button_link' => '/kontak',
        ])->save();

        $categories = [
            [
                'title' => 'Pendaftaran Pelatihan',
                'subtitle' => 'Informasi tahap seleksi hingga kehadiran peserta.',
                'icon' => 'fas fa-graduation-cap',
                'urutan' => 1,
                'items' => [
                    ['question' => 'Bagaimana cara mendaftar pelatihan?', 'answer' => '<p>Pendaftaran dapat dilakukan melalui halaman jadwal pelatihan. Pilih program yang diminati kemudian isi formulir online. Kami akan mengirimkan email konfirmasi apabila Anda lolos seleksi administrasi.</p>'],
                    ['question' => 'Apa saja dokumen yang harus disiapkan?', 'answer' => '<p>Umumnya diperlukan KTP, ijazah terakhir, pas foto, dan surat keterangan pengalaman jika ada. Beberapa program menambahkan syarat khusus yang tertera pada detail pelatihan.</p>'],
                ],
            ],
            [
                'title' => 'Layanan Sertifikasi',
                'subtitle' => 'Proses uji kompetensi dan penerbitan sertifikat.',
                'icon' => 'fas fa-certificate',
                'urutan' => 2,
                'items' => [
                    ['question' => 'Siapa yang dapat mengikuti uji kompetensi?', 'answer' => '<p>Peserta minimal berusia 17 tahun dan memiliki bukti pelatihan/ pengalaman kerja sesuai skema. Calon peserta wajib membawa dokumen pendukung saat verifikasi.</p>'],
                    ['question' => 'Berapa lama masa berlaku sertifikat?', 'answer' => '<p>Sertifikat kompetensi berlaku selama 3 tahun dan dapat diperpanjang dengan mengikuti resertifikasi sesuai ketentuan LSP.</p>'],
                ],
            ],
            [
                'title' => 'Layanan Pengaduan',
                'subtitle' => 'Kanal resmi untuk menyampaikan masukan.',
                'icon' => 'fas fa-headset',
                'urutan' => 3,
                'items' => [
                    ['question' => 'Bagaimana melaporkan keluhan layanan?', 'answer' => '<p>Gunakan formulir pengaduan pada halaman kontak atau datang langsung ke loket informasi. Petugas kami akan memberikan nomor tiket untuk dipantau.</p>'],
                    ['question' => 'Kapan pengaduan mendapatkan tanggapan?', 'answer' => '<p>Pengaduan ditindaklanjuti maksimal 3 hari kerja setelah diterima. Tim kami akan menghubungi pelapor melalui kanal yang dicantumkan.</p>'],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $items = $categoryData['items'];
            unset($categoryData['items']);

            $category = FaqCategory::updateOrCreate(
                ['title' => $categoryData['title']],
                $categoryData + ['is_active' => true]
            );

            foreach ($items as $index => $itemData) {
                FaqItem::updateOrCreate(
                    [
                        'faq_category_id' => $category->id,
                        'question' => $itemData['question'],
                    ],
                    $itemData + ['urutan' => $index, 'is_active' => true]
                );
            }
        }
    }
}
