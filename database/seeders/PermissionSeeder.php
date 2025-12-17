<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'manage-users', 'label' => 'Kelola Pengguna', 'module' => 'system'],
            ['name' => 'manage-audit', 'label' => 'Melihat Log Aktivitas', 'module' => 'system'],
            ['name' => 'manage-access', 'label' => 'Kelola Role & Permission', 'module' => 'system'],
            ['name' => 'approve-content', 'label' => 'Menyetujui Konten', 'module' => 'konten'],
            ['name' => 'manage-seo', 'label' => 'Kelola SEO', 'module' => 'konten'],
            ['name' => 'manage-berita', 'label' => 'Kelola Berita & Artikel', 'module' => 'konten'],
            ['name' => 'manage-program', 'label' => 'Kelola Program Pelatihan', 'module' => 'konten'],
            ['name' => 'manage-publication', 'label' => 'Kelola Publikasi', 'module' => 'konten'],
            ['name' => 'manage-gallery', 'label' => 'Kelola Media & Galeri', 'module' => 'konten'],
            ['name' => 'manage-surveys', 'label' => 'Kelola Survey & Formulir', 'module' => 'survey'],
            ['name' => 'view-survey-analytics', 'label' => 'Lihat Analitik Survey', 'module' => 'survey'],
            ['name' => 'manage-faq', 'label' => 'Kelola FAQ', 'module' => 'layanan'],
            ['name' => 'manage-public-service', 'label' => 'Kelola Pelayanan Publik', 'module' => 'layanan'],
            ['name' => 'manage-ppid', 'label' => 'Kelola PPID', 'module' => 'layanan'],
            ['name' => 'manage-settings', 'label' => 'Kelola Pengaturan Situs', 'module' => 'system'],
            ['name' => 'access-admin', 'label' => 'Akses Panel Admin', 'module' => 'system'],
            ['name' => 'access-alumni-forum', 'label' => 'Akses Forum Alumni', 'module' => 'alumni'],
            ['name' => 'moderate-alumni-forum', 'label' => 'Moderasi Forum Alumni', 'module' => 'alumni'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission['name']], $permission);
        }

        $rolePermissions = [
            'superadmin' => Permission::pluck('id')->all(),
            'admin' => Permission::whereNotIn('name', ['manage-users', 'manage-audit', 'manage-access'])->pluck('id')->all(),
            'editor' => Permission::whereIn('name', [
                'manage-berita',
                'manage-program',
                'manage-publication',
                'manage-gallery',
                'manage-faq',
                'manage-surveys',
            ])->pluck('id')->all(),
            'viewer' => Permission::where('name', 'access-admin')->pluck('id')->all(),
            'alumni' => Permission::where('name', 'access-alumni-forum')->pluck('id')->all(),
        ];

        foreach ($rolePermissions as $roleName => $permissionIds) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->permissions()->sync($permissionIds);
            }
        }
    }
}
