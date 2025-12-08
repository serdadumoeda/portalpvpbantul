<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    protected function canManage(User $user): bool
    {
        return $user->hasPermission('manage-access');
    }

    public function viewAny(User $user): bool
    {
        return $this->canManage($user);
    }

    public function view(User $user, Permission $permission): bool
    {
        return $this->canManage($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Permission $permission): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $this->canManage($user);
    }
}
