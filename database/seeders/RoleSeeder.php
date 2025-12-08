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
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['label' => $role['label']]
            );
        }
    }
}
