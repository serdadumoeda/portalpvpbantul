<?php

namespace Database\Seeders;

use App\Models\WeeklyChallenge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WeeklyChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $start = Carbon::now()->startOfWeek();
        $end = (clone $start)->endOfWeek();

        WeeklyChallenge::updateOrCreate(
            ['start_date' => $start->toDateString(), 'end_date' => $end->toDateString()],
            [
                'title' => 'Cerita Kolaborasi Terbaik',
                'question' => 'Bagikan pengalaman kamu bekerja sama dengan lembaga lain atau alumni lain, dan apa pelajaran terbaiknya?',
                'is_active' => true,
            ]
        );
    }
}
