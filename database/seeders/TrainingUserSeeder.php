<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TrainingUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Instruktur Demo',
                'email' => 'instruktur@example.com',
                'password' => 'instruktur123',
                'role' => 'instructor',
            ],
            [
                'name' => 'Peserta Demo',
                'email' => 'peserta@example.com',
                'password' => 'peserta123',
                'role' => 'participant',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                ]
            );

            if (! empty($data['role'])) {
                $role = Role::where('name', $data['role'])->first();
                if ($role) {
                    $user->roles()->syncWithoutDetaching([$role->id]);
                }
            }
        }
    }
}
