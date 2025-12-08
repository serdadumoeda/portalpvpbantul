<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiUserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createTokenUser(): User
    {
        $permission = Permission::create(['name' => 'manage-users', 'label' => 'Kelola Pengguna']);
        $role = Role::create(['name' => 'api-admin', 'label' => 'API Admin']);
        $role->permissions()->attach($permission->id);

        $user = User::factory()->create([
            'email' => 'apiadmin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user->syncRoles([$role->id]);

        return $user;
    }

    public function test_api_login_and_list_users_with_permission(): void
    {
        $admin = $this->createTokenUser();

        $loginResponse = $this->postJson('/api/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $loginResponse->assertOk();
        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token);

        $listResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/users');

        $listResponse->assertOk()->assertJsonFragment(['email' => $admin->email]);
    }

    public function test_api_access_denied_without_permission(): void
    {
        $user = User::factory()->create([
            'email' => 'limited@example.com',
            'password' => Hash::make('password123'),
        ]);

        $token = $user->generateApiToken();

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/admin/users')
            ->assertForbidden();
    }
}
