<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('manage-audit');
    }

    public function view(User $user, ActivityLog $log): bool
    {
        return $user->hasPermission('manage-audit');
    }

    public function delete(User $user, ActivityLog $log): bool
    {
        return $user->hasPermission('manage-audit');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('manage-audit');
    }
}
