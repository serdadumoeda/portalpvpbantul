<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index()
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::with('permissions')->orderBy('label')->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);

        return view('admin.roles.form', [
            'title' => 'Tambah Role',
            'action' => route('admin.roles.store'),
            'method' => 'POST',
            'role' => new Role(),
            'permissions' => Permission::orderBy('module')->orderBy('label')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);

        $data = $this->validateData($request);

        $role = Role::create([
            'name' => $data['name'],
            'label' => $data['label'] ?? null,
        ]);
        $role->permissions()->sync($data['permissions'] ?? []);

        $this->logger->log($request->user(), 'role.created', "Membuat role {$role->name}", $role, ['permissions' => $data['permissions'] ?? []]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        return view('admin.roles.form', [
            'title' => 'Edit Role',
            'action' => route('admin.roles.update', $role),
            'method' => 'PUT',
            'role' => $role->load('permissions'),
            'permissions' => Permission::orderBy('module')->orderBy('label')->get(),
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $data = $this->validateData($request, $role->id);

        $role->update([
            'name' => $data['name'],
            'label' => $data['label'] ?? null,
        ]);
        $role->permissions()->sync($data['permissions'] ?? []);

        $this->logger->log($request->user(), 'role.updated', "Memperbarui role {$role->name}", $role, ['permissions' => $data['permissions'] ?? []]);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Request $request, Role $role)
    {
        $this->authorize('delete', $role);

        if ($role->name === 'superadmin') {
            return back()->with('error', 'Role superadmin tidak dapat dihapus.');
        }

        $role->delete();

        $this->logger->log($request->user(), 'role.deleted', "Menghapus role {$role->name}", $role);

        return redirect()->route('admin.roles.index')->with('success', 'Role berhasil dihapus.');
    }

    private function validateData(Request $request, ?string $roleId = null): array
    {
        $uniqueRule = 'unique:roles,name';
        if ($roleId) {
            $uniqueRule .= ',' . $roleId . ',id';
        }

        return $request->validate([
            'name' => ['required', 'alpha_dash', 'max:50', $uniqueRule],
            'label' => ['nullable', 'string', 'max:100'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);
    }
}
