<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    protected function canManage(User $user): bool
    {
        return $user->hasPermission('manage-access');
    }

    public function viewAny(User $user): bool
    {
        return $this->canManage($user);
    }

    public function view(User $user, Role $role): bool
    {
        return $this->canManage($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Role $role): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, Role $role): bool
    {
        return $this->canManage($user);
    }
}
