<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage-users');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermission('manage-users');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('manage-users');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('manage-users');
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermission('manage-users');
    }

    public function impersonate(User $user, User $model): bool
    {
        if ($user->is($model)) {
            return false;
        }

        if ($model->hasRole('superadmin') && ! $user->hasRole('superadmin')) {
            return false;
        }

        return $user->hasPermission('impersonate-users');
    }
}
