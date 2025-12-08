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
    }
}
