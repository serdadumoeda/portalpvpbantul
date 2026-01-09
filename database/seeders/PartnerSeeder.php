<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'nama' => 'PT Maju Bersama',
                'logo' => 'https://placehold.co/240x120?text=Partner+1',
                'tautan' => 'https://example.com/partner-1',
                'urutan' => 1,
            ],
            [
                'nama' => 'CV Kreatif Nusantara',
                'logo' => 'https://placehold.co/240x120?text=Partner+2',
                'tautan' => 'https://example.com/partner-2',
                'urutan' => 2,
            ],
            [
                'nama' => 'Industri Digital Sejahtera',
                'logo' => 'https://placehold.co/240x120?text=Partner+3',
                'tautan' => 'https://example.com/partner-3',
                'urutan' => 3,
            ],
        ];

        foreach ($items as $item) {
            Partner::updateOrCreate(
                ['nama' => $item['nama']],
                $item + [
                    'is_active' => true,
                    'status' => 'published',
                ]
            );
        }
    }
}
