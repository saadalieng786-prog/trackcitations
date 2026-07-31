<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Policies\Policies;

use App\Models\Driver;
use App\Models\User;

class DriverPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Driver $driver): bool
    {
        //
        if ($user->isInternalAdmin()) {
            return true;
        } else if ($user->isCompanyAdmin()) {
            return $user->canAccessCompany($driver->company_id);
        } else if ($user->hasRole(User::ROLE_DRIVER)) {
            return $user->roleable?->id === $driver->id;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        $currentUser = auth()->user();
        if ($currentUser->isInternalAdmin()) {
            return true;
        } else if ($currentUser->isCompanyAdmin()) {
            return $currentUser->roleable->companiesCountWithWriteAccess() > 0;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Driver $driver): bool
    {
        //
        if ($user->isInternalAdmin()) {
            return true;
        } else if ($user->isCompanyAdmin()) {
            return $user->canWriteCompany($driver->company_id);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Driver $driver): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Driver $driver): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Driver $driver): bool
    {
        //
    }
}
