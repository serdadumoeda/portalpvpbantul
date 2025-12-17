<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            ForumBadgeSeeder::class,
            WeeklyChallengeSeeder::class,
            ProgramSeeder::class,
            BeritaSeeder::class,
            ProfileSeeder::class,
            CertificationSeeder::class,
            JobVacancySeeder::class,
            InfographicSeeder::class,
            PublicationSeeder::class,
            PublicServiceSeeder::class,
            FaqSeeder::class,
            ContactSeeder::class,
            PpidSeeder::class,
            SurveySeeder::class,
        ]);
    }
}
