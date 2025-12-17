<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'superadmin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $editorRole = Role::where('name', 'editor')->first();
        $alumniRole = Role::where('name', 'alumni')->first();

        $admin = User::updateOrCreate(
            ['email' => 'admin@bpvp.com'],
            [
                'name' => 'Administrator PVP',
                'password' => 'password123',
            ]
        );
        $admin->syncRoles([$superAdminRole?->id, $adminRole?->id]);

        $editor = User::updateOrCreate(
            ['email' => 'editor@bpvp.com'],
            [
                'name' => 'Editor Konten',
                'password' => 'password123',
            ]
        );
        $editor->syncRoles([$editorRole?->id]);

        $moderatorRole = Role::where('name', 'moderator')->first();

        $moderator = User::updateOrCreate(
            ['email' => 'moderator@bpvp.com'],
            [
                'name' => 'Moderator Forum',
                'password' => 'moderator123',
            ]
        );
        $moderator->syncRoles([$moderatorRole?->id]);

        $alumni = User::updateOrCreate(
            ['email' => 'alumni@bpvp.com'],
            [
                'name' => 'Alumni Satpel PVP Bantul',
                'password' => 'alumni2025',
            ]
        );
        $alumni->syncRoles([$alumniRole?->id]);
    }
}
