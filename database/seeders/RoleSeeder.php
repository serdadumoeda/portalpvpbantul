<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'superadmin', 'label' => 'Super Admin'],
            ['name' => 'admin', 'label' => 'Administrator'],
            ['name' => 'editor', 'label' => 'Editor Konten'],
            ['name' => 'viewer', 'label' => 'Viewer'],
            ['name' => 'moderator', 'label' => 'Moderator Forum'],
            ['name' => 'reviewer', 'label' => 'Reviewer Konten'],
            ['name' => 'instructor', 'label' => 'Instruktur'],
            ['name' => 'participant', 'label' => 'Peserta Pelatihan'],
            ['name' => 'alumni', 'label' => 'Alumni'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['label' => $role['label']]
            );
        }
    }
}
