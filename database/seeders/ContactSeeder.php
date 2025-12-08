<?php

namespace Database\Seeders;

use App\Models\ContactChannel;
use App\Models\ContactSetting;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $setting = ContactSetting::first() ?? new ContactSetting();
        $setting->fill([
            'hero_title' => 'Hubungi Kami',
            'hero_subtitle' => 'Hubungi kami dan kami siap membantu Anda!',
            'hero_description' => 'Hubungi kami dan kami siap membantu Anda! Tim kami siap memberikan informasi layanan, kolaborasi, dan dukungan lainnya.',
            'hero_button_text' => 'Lihat Selengkapnya',
            'hero_button_link' => '#kontak',
            'map_title' => 'Temukan Kami',
            'map_description' => 'Temukan kami dengan menemukan alamat dan informasi kontak kami dengan mudah.',
            'map_embed' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.373638335064!2d110.35274827602823!3d-7.528640274875234!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a56567ed1d897%3A0xe7b2b65b2b4df1b4!2sBPVP%20Bantul!5e0!3m2!1sen!2sid!4v1701481372000!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'info_section_title' => 'Layanan Informasi Untuk Anda',
            'info_section_description' => 'Layanan informasi untuk Anda dengan sumber informasi terpercaya untuk memenuhi kebutuhan Anda.',
            'cta_title' => 'Anda Siap Tingkatkan Skill dengan Kami?',
            'cta_description' => 'Pilih topik pelatihan sesuai minatmu dan segera pelajari materinya untuk menguasai keahlian yang kamu butuhkan.',
            'cta_primary_text' => 'Cek Topik & Jadwal Kelasnya',
            'cta_primary_link' => '/pelatihan/katalog',
            'cta_secondary_text' => 'Tanya via WhatsApp',
            'cta_secondary_link' => 'https://wa.me/6281227851568',
        ])->save();

        $channels = [
            [
                'title' => 'Office Address',
                'subtitle' => 'Jl. Raya Tangkuban Perahu Km. 04, Desa Cikole',
                'label' => 'Kec. Lembang Kab. Bandung Barat 40391',
                'icon' => 'fas fa-map-marker-alt',
                'urutan' => 1,
            ],
            [
                'title' => 'Phone Number',
                'subtitle' => 'Telepon Kantor',
                'label' => '(+6222) 2785-1158',
                'icon' => 'fas fa-phone',
                'urutan' => 2,
            ],
            [
                'title' => 'WhatsApp',
                'subtitle' => 'Hubungi layanan kami',
                'label' => '(+62857-9602-6252)',
                'icon' => 'fab fa-whatsapp',
                'link' => 'https://wa.me/6285796026252',
                'urutan' => 3,
            ],
            [
                'title' => 'Email',
                'subtitle' => 'bpvpbandungraya@kemnaker.go.id',
                'label' => 'Klik untuk kirim email',
                'icon' => 'fas fa-envelope',
                'link' => 'mailto:bpvpbandungraya@kemnaker.go.id',
                'urutan' => 4,
            ],
            [
                'title' => 'YouTube',
                'subtitle' => 'Satpel PVP Bantul',
                'label' => 'Channel Resmi',
                'icon' => 'fab fa-youtube',
                'link' => 'https://www.youtube.com/',
                'urutan' => 5,
            ],
            [
                'title' => 'Instagram',
                'subtitle' => '@bpvp.bandungraya',
                'label' => 'Ikuti kami',
                'icon' => 'fab fa-instagram',
                'link' => 'https://instagram.com/bpvp.bandungraya',
                'urutan' => 6,
            ],
        ];

        foreach ($channels as $data) {
            ContactChannel::updateOrCreate(
                ['title' => $data['title']],
                $data + ['is_active' => true]
            );
        }
    }
}
