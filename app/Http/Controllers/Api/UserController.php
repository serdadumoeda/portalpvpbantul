<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $users = User::with('roles')->orderBy('name')->get();

        return response()->json($users);
    }

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return response()->json($user->load('roles'));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $data = $this->validatePayload($request);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        $user->syncRoles($data['roles']);

        $this->logger->log($request->user(), 'api.user.created', "API membuat user {$user->email}", $user, ['roles' => $data['roles']]);

        return response()->json($user->load('roles'), 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $data = $this->validatePayload($request, $user->id, isUpdate: true);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
        $user->syncRoles($data['roles']);

        $this->logger->log($request->user(), 'api.user.updated', "API memperbarui user {$user->email}", $user, ['roles' => $data['roles']]);

        return response()->json($user->load('roles'));
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        if ($request->user()->is($user)) {
            return response()->json(['message' => 'Tidak dapat menghapus akun sendiri'], 422);
        }

        $user->delete();

        $this->logger->log($request->user(), 'api.user.deleted', "API menghapus user {$user->email}", $user);

        return response()->json(['message' => 'User dihapus']);
    }

    private function validatePayload(Request $request, ?string $userId = null, bool $isUpdate = false): array
    {
        $uniqueEmailRule = 'unique:users,email';
        if ($userId) {
            $uniqueEmailRule .= ',' . $userId . ',id';
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $uniqueEmailRule],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['exists:roles,id'],
        ];

        if ($isUpdate) {
            $rules['password'] = ['nullable', 'string', 'min:8'];
        } else {
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        return $request->validate($rules);
    }
}
