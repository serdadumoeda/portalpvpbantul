<?php

namespace Database\Seeders;

use App\Models\PublicationCategory;
use App\Models\PublicationItem;
use App\Models\PublicationSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PublicationSeeder extends Seeder
{
    public function run(): void
    {
        $setting = PublicationSetting::firstOrCreate([], [
            'hero_title' => 'Publikasi',
            'hero_description' => 'Publikasi resmi mengenai layanan, penghargaan, dan capaian Satpel PVP Bantul.',
            'hero_button_text' => 'Lihat Publikasi',
            'hero_button_link' => '#publikasi',
            'hero_image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=1500&q=80',
            'intro_title' => 'Pencapaian Satpel PVP Bantul',
            'intro_description' => 'Dokumentasi penghargaan, laporan survei, hingga majalah resmi.',
            'alumni_title' => 'Alumni Kami',
            'alumni_description' => 'Cerita alumni yang menginspirasi dengan beragam program pelatihan.',
            'alumni_video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'downloads_title' => 'Materi Downloads',
            'downloads_description' => 'Kumpulan materi resmi yang dapat Anda unduh secara gratis.',
        ]);

        $categoriesData = [
            [
                'name' => 'Pencapaian Satpel PVP Bantul',
                'slug' => 'pencapaian',
                'layout' => 'cards',
                'subtitle' => 'Penghargaan & Sertifikasi',
                'description' => 'Pengakuan dari mitra nasional dan internasional.',
                'columns' => 4,
                'urutan' => 1,
                'items' => [
                    ['title' => 'Anugerah ASN BSB 2024', 'badge' => 'Penghargaan', 'description' => 'Penghargaan bagi Satpel PVP Bantul atas inovasi layanan.', 'button_text' => 'Lihat', 'button_link' => '#'],
                    ['title' => 'Sertifikasi ILO 2023', 'badge' => 'Sertifikasi', 'description' => 'Pengakuan internasional terhadap kurikulum pelatihan.', 'button_text' => 'Lihat', 'button_link' => '#'],
                ],
            ],
            [
                'name' => 'Survei Kepuasan Masyarakat',
                'slug' => 'survey',
                'layout' => 'infographic',
                'subtitle' => 'Dashboard PBK',
                'description' => 'Representasi visual hasil survei layanan setiap tahun.',
                'urutan' => 2,
                'columns' => 2,
                'items' => [
                    ['title' => 'Indeks Kepuasan 2023', 'badge' => 'IKM', 'description' => 'Nilai IKM berada pada kategori A (Sangat Baik).', 'extra' => ['Skor 94,5', 'Responden 230 org']],
                    ['title' => 'Tingkat Rekomendasi', 'badge' => 'NPS', 'description' => 'Mayoritas responden siap merekomendasikan Satpel PVP Bantul.', 'extra' => ['Promoter 82%', 'Passive 10%']],
                ],
            ],
            [
                'name' => 'Majalah BPVP',
                'slug' => 'majalah',
                'layout' => 'cards',
                'subtitle' => 'Majalah Digital',
                'columns' => 4,
                'urutan' => 3,
                'items' => [
                    ['title' => 'Majalah BPVP Edisi 01', 'description' => 'Highlight kegiatan triwulan I.', 'button_text' => 'Baca', 'button_link' => '#'],
                    ['title' => 'Majalah BPVP Edisi 02', 'description' => 'Cerita alumni inspiratif.', 'button_text' => 'Baca', 'button_link' => '#'],
                ],
            ],
            [
                'name' => 'Laporan Kinerja',
                'slug' => 'laporan',
                'layout' => 'cards',
                'subtitle' => 'Laporan Tahunan',
                'columns' => 4,
                'urutan' => 4,
                'items' => [
                    ['title' => 'Laporan 2023', 'description' => 'Ikhtisar kinerja program dan layanan.', 'button_text' => 'Unduh', 'button_link' => '#'],
                    ['title' => 'Laporan 2022', 'description' => 'Data capaian dan strategi.', 'button_text' => 'Unduh', 'button_link' => '#'],
                ],
            ],
            [
                'name' => 'Alumni Kami',
                'slug' => 'alumni',
                'layout' => 'alumni',
                'subtitle' => 'Testimoni & Karier',
                'urutan' => 5,
                'items' => [
                    ['title' => 'Rahma - Barista Profesional', 'description' => 'Alumni perhotelan yang kini bekerja di hotel bintang lima.', 'button_text' => 'Tonton', 'button_link' => '#'],
                    ['title' => 'Ardi - Teknisi Pendingin', 'description' => 'Mendirikan usaha jasa pendingin di Sleman.', 'button_text' => 'Tonton', 'button_link' => '#'],
                ],
            ],
            [
                'name' => $setting->downloads_title ?? 'Materi Downloads',
                'slug' => 'downloads',
                'layout' => 'downloads',
                'subtitle' => 'Materi',
                'urutan' => 6,
                'items' => [
                    ['title' => 'Panduan Pendaftaran Pelatihan', 'description' => 'Petunjuk lengkap pendaftaran daring.', 'button_text' => 'Unduh', 'button_link' => '#'],
                    ['title' => 'Katalog Program 2024', 'description' => 'Daftar lengkap kejuruan dan jadwal.', 'button_text' => 'Unduh', 'button_link' => '#'],
                ],
            ],
        ];

        foreach ($categoriesData as $categoryData) {
            $items = $categoryData['items'];
            unset($categoryData['items']);
            $categoryData['slug'] = Str::slug($categoryData['slug']);
            $category = PublicationCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );

            foreach ($items as $index => $item) {
                PublicationItem::updateOrCreate(
                    ['publication_category_id' => $category->id, 'title' => $item['title']],
                    $item + ['urutan' => $index]
                );
            }
        }
    }
}
