<?php

return [
    'indicators' => [
        'reach' => [
            'key' => 'reach',
            'label' => 'Total Reach Medsos',
            'unit' => 'Akun',
            'category' => 'Awareness',
            'description' => 'Jumlah akun unik yang melihat konten BPVP Bantul di kanal digital dalam satu bulan.',
            'target_text' => 'Naik 5% setiap bulan',
            'owner' => 'Tim Medsos',
            'step' => 1,
        ],
        'registrant' => [
            'key' => 'registrant',
            'label' => 'Total Pendaftar Siap Kerja',
            'unit' => 'Orang',
            'category' => 'Demand',
            'description' => 'Jumlah calon peserta yang mendaftar melalui Sistem Siap Kerja.',
            'target_text' => 'Minimal 300% terhadap kuota (1:3)',
            'owner' => 'Kios 3in1',
            'step' => 1,
        ],
        'rating' => [
            'key' => 'rating',
            'label' => 'Rating Google Maps',
            'unit' => 'Skor',
            'category' => 'Impact',
            'description' => 'Rata-rata ulasan publik terhadap layanan informasi BPVP Bantul.',
            'target_text' => 'Minimal skor 4.6',
            'owner' => 'Tim Medsos',
            'step' => 0.01,
            'max' => 5,
        ],
        'partner' => [
            'key' => 'partner',
            'label' => 'Jumlah Mitra Baru',
            'unit' => 'PT',
            'category' => 'Kemitraan',
            'description' => 'Industri baru yang menjalin komunikasi/MoU setelah mengenal profil BPVP.',
            'target_text' => 'Minimal 3 mitra baru / bulan',
            'owner' => 'Subkoor Pemberdayaan',
            'step' => 1,
        ],
    ],
];
