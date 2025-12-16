<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $permission = Permission::firstOrCreate(
                ['name' => 'access-admin'],
                ['label' => 'Akses Panel Admin', 'module' => 'system']
            );

            Role::whereIn('name', ['superadmin', 'admin', 'editor', 'viewer'])
                ->get()
                ->each(function (Role $role) use ($permission) {
                    if (!$role->permissions()->where('permission_id', $permission->id)->exists()) {
                        $role->permissions()->attach($permission->id);
                    }
                });
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            $permission = Permission::where('name', 'access-admin')->first();

            if ($permission) {
                $permission->roles()->detach();
                $permission->delete();
            }
        });
    }
};
