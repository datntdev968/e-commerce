<?php

namespace App\Policies\Role;

use App\Models\User;
use Spatie\Permission\Models\Role;


class RolePolicy
{

    public function hasAccess(User $user, $permission)
    {
        return $user->isAdmin() || $user->hasPermissionTo($permission);
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $this->hasAccess($user, 'Role View');
    }

    public function viewAny(User $user): bool
    {
        return $this->hasAccess($user, 'Role ViewAny');
    }
}
