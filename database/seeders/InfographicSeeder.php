<?php

namespace Database\Seeders;

use App\Models\InfographicCard;
use App\Models\InfographicEmbed;
use App\Models\InfographicMetric;
use App\Models\InfographicYear;
use Illuminate\Database\Seeder;

class InfographicSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'tahun' => '2022',
                'title' => 'Infografis Data Alumni 2022',
                'headline' => 'Infografis Data Alumni',
                'description' => 'Rangkuman distribusi lulusan dan penempatan kerja sepanjang tahun 2022.',
                'hero_button_text' => 'Unduh Ringkasan',
                'hero_button_link' => '#',
                'hero_image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1200&q=70',
                'urutan' => 1,
                'metrics' => [
                    ['label' => 'Total Alumni', 'value' => '3.120'],
                    ['label' => 'Kejuruan', 'value' => '28'],
                    ['label' => 'Lembaga Mitra', 'value' => '250'],
                    ['label' => 'Terserap Industri', 'value' => '1.850'],
                    ['label' => 'Wirausaha', 'value' => '870'],
                    ['label' => 'Pelatihan PKW', 'value' => '80'],
                ],
                'cards' => [
                    ['title' => 'Top Bidang Favorit', 'entries' => ['Teknik Mesin', 'Tata Boga', 'Digital Marketing']],
                    ['title' => 'Top Penempatan Wilayah', 'entries' => ['DIY', 'Jawa Tengah', 'Jawa Barat']],
                    ['title' => 'Top Kompetensi', 'entries' => ['Operator CNC', 'Barista', 'Desainer Grafis']],
                ],
                'embeds' => [
                    ['title' => 'Dashboard PBK 2022', 'url' => 'https://lookerstudio.google.com/embed/reporting/placeholder-2022', 'height' => 650],
                ],
            ],
            [
                'tahun' => '2023',
                'title' => 'Infografis Data Alumni 2023',
                'headline' => 'Infografis Data Alumni',
                'description' => 'Dashboard terbaru dengan highlight penempatan kerja dan capaian program.',
                'hero_button_text' => 'Lihat Dashboard',
                'hero_button_link' => '#',
                'hero_image' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1200&q=70',
                'urutan' => 2,
                'metrics' => [
                    ['label' => 'Total Alumni', 'value' => '3.678'],
                    ['label' => 'Kejuruan', 'value' => '30'],
                    ['label' => 'Lembaga Mitra', 'value' => '275'],
                    ['label' => 'Terserap Industri', 'value' => '1.950'],
                    ['label' => 'Wirausaha', 'value' => '1.159'],
                    ['label' => 'Pelatihan PKW', 'value' => '86'],
                ],
                'cards' => [
                    ['title' => 'Top 5 Program', 'entries' => ['Teknik Las', 'Teknik Pendingin', 'Perhotelan', 'Desain Grafis', 'Bakery']],
                    ['title' => 'Top Mitra Industri', 'entries' => ['PT Karya Baja', 'Kopi Nusantara', 'Hotel Merapi', 'PT Digital Kreasi']],
                    ['title' => 'Persentase Gender', 'entries' => ['Laki-laki 58%', 'Perempuan 42%']],
                ],
                'embeds' => [
                    ['title' => 'Dashboard PBK 2023', 'url' => 'https://lookerstudio.google.com/embed/reporting/placeholder-2023', 'height' => 680],
                ],
            ],
        ];

        foreach ($data as $yearData) {
            $metrics = $yearData['metrics'] ?? [];
            $cards = $yearData['cards'] ?? [];
            $embeds = $yearData['embeds'] ?? [];
            unset($yearData['metrics'], $yearData['cards'], $yearData['embeds']);

            $year = InfographicYear::updateOrCreate(
                ['tahun' => $yearData['tahun']],
                $yearData
            );

            foreach ($metrics as $index => $metric) {
                InfographicMetric::updateOrCreate(
                    ['infographic_year_id' => $year->id, 'label' => $metric['label']],
                    ['value' => $metric['value'], 'urutan' => $index]
                );
            }

            foreach ($cards as $index => $card) {
                InfographicCard::updateOrCreate(
                    ['infographic_year_id' => $year->id, 'title' => $card['title']],
                    ['entries' => $card['entries'], 'urutan' => $index]
                );
            }

            foreach ($embeds as $index => $embed) {
                InfographicEmbed::updateOrCreate(
                    ['infographic_year_id' => $year->id, 'title' => $embed['title']],
                    ['url' => $embed['url'], 'height' => $embed['height'], 'urutan' => $index]
                );
            }
        }
    }
}
