<?php

namespace App\Policies\Catalogue;

use App\Models\Catalogue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CataloguePolicy
{

    public function hasAccess(User $user, $permission)
    {
        return $user->isAdmin() || $user->hasPermissionTo($permission);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasAccess($user, 'Catalogue ViewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Catalogue $catalogue): bool
    {
        return $this->hasAccess($user, 'Catalogue View');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasAccess($user, 'Catalogue Store');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Catalogue $catalogue): bool
    {
        return $this->hasAccess($user, 'Catalogue Update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Catalogue $catalogue): bool
    {
        return $this->hasAccess($user, 'Catalogue Delete');
    }
}
