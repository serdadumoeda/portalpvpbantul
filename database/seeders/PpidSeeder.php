<?php

namespace Database\Seeders;

use App\Models\PpidHighlight;
use App\Models\PpidSetting;
use Illuminate\Database\Seeder;

class PpidSeeder extends Seeder
{
    public function run(): void
    {
        $setting = PpidSetting::first() ?? new PpidSetting();
        $setting->fill([
            'hero_title' => 'Profil PPID',
            'hero_subtitle' => 'Pejabat Pengelola Informasi dan Dokumentasi',
            'hero_description' => 'PPID Satpel PVP Bantul bertugas dalam pengelolaan, pendokumentasian, dan pelayanan informasi publik.',
            'hero_button_text' => 'Lihat Selengkapnya',
            'hero_button_link' => '#form',
            'profile_title' => 'Profil PPID',
            'profile_description' => 'Pembentukan PPID merupakan amanat Undang-undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik guna mewujudkan pemerintahan yang transparan dan akuntabel.',
            'form_title' => 'Permohonan Informasi Publik',
            'form_description' => 'Silakan isi formulir berikut untuk mengajukan permohonan informasi.',
            'form_embed' => '<iframe src="https://tally.so/embed/mVOZPg?alignLeft=1&hideTitle=1&transparentBackground=1" width="100%" height="800" frameborder="0" marginheight="0" marginwidth="0" title="Form PPID"></iframe>',
        ])->save();

        $highlights = [
            ['title' => 'Transparansi', 'description' => 'Gerbang keterbukaan informasi publik untuk masyarakat.', 'icon' => 'fas fa-shield-alt', 'urutan' => 1],
            ['title' => 'Layanan Publik', 'description' => 'Permohonan informasi dilayani secara cepat dan terukur.', 'icon' => 'fas fa-file-signature', 'urutan' => 2],
            ['title' => 'Akuntabilitas', 'description' => 'Dikelola sesuai peraturan dan siap dipertanggungjawabkan.', 'icon' => 'fas fa-tasks', 'urutan' => 3],
        ];

        foreach ($highlights as $highlight) {
            PpidHighlight::updateOrCreate(
                ['title' => $highlight['title']],
                $highlight + ['is_active' => true]
            );
        }
    }
}
