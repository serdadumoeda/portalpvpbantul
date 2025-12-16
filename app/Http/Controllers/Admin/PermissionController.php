<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index()
    {
        $this->authorize('viewAny', Permission::class);

        $permissions = Permission::with('roles')->orderBy('module')->orderBy('label')->get();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        $this->authorize('create', Permission::class);

        return view('admin.permissions.form', [
            'title' => 'Tambah Permission',
            'action' => route('admin.permissions.store'),
            'method' => 'POST',
            'permission' => new Permission(),
            'roles' => Role::orderBy('label')->get(),
            'labelOptions' => Permission::whereNotNull('label')->distinct()->pluck('label')->filter()->values(),
            'moduleOptions' => Permission::whereNotNull('module')->distinct()->pluck('module')->filter()->values(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);

        $data = $this->validateData($request);

        $permission = Permission::create($data);
        $permission->roles()->sync($request->input('roles', []));

        $this->logger->log($request->user(), 'permission.created', "Membuat permission {$permission->name}", $permission, ['roles' => $request->input('roles', [])]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil ditambahkan.');
    }

    public function edit(Permission $permission)
    {
        $this->authorize('update', $permission);

        return view('admin.permissions.form', [
            'title' => 'Edit Permission',
            'action' => route('admin.permissions.update', $permission),
            'method' => 'PUT',
            'permission' => $permission->load('roles'),
            'roles' => Role::orderBy('label')->get(),
            'labelOptions' => Permission::whereNotNull('label')->distinct()->pluck('label')->filter()->values(),
            'moduleOptions' => Permission::whereNotNull('module')->distinct()->pluck('module')->filter()->values(),
        ]);
    }

    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);

        $data = $this->validateData($request, $permission->id);

        $permission->update($data);
        $permission->roles()->sync($request->input('roles', []));

        $this->logger->log($request->user(), 'permission.updated', "Memperbarui permission {$permission->name}", $permission, ['roles' => $request->input('roles', [])]);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy(Request $request, Permission $permission)
    {
        $this->authorize('delete', $permission);

        $permission->delete();

        $this->logger->log($request->user(), 'permission.deleted', "Menghapus permission {$permission->name}", $permission);

        return redirect()->route('admin.permissions.index')->with('success', 'Permission berhasil dihapus.');
    }

    private function validateData(Request $request, ?string $permissionId = null): array
    {
        $uniqueRule = 'unique:permissions,name';
        if ($permissionId) {
            $uniqueRule .= ',' . $permissionId . ',id';
        }

        return $request->validate([
            'name' => ['required', 'alpha_dash', 'max:100', $uniqueRule],
            'label' => ['nullable', 'string', 'max:150'],
            'module' => ['nullable', 'string', 'max:100'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);
    }
}
