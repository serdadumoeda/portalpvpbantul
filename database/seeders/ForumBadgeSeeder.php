<?php

namespace Database\Seeders;

use App\Models\ForumBadge;
use Illuminate\Database\Seeder;

class ForumBadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['name' => 'kontributor-aktif', 'label' => 'Kontributor Aktif', 'description' => 'Berhasil memberikan minimal 5 kontribusi yang disetujui.'],
            ['name' => 'mentor-alumni', 'label' => 'Mentor Alumni', 'description' => 'Menjadi mentor melalui diskusi yang sering membantu alumni lain.'],
        ];

        foreach ($badges as $badge) {
            ForumBadge::updateOrCreate(['name' => $badge['name']], $badge);
        }
    }
}
