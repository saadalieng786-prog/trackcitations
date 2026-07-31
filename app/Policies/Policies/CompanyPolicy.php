<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Policies\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
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
    public function view(User $user, Company $company): bool
    {
        //
        if ($user->isInternalAdmin()) {
            return true;
        } else if ($user->isCompanyAdmin()) {
            return $user->canAccessCompany($company->id);
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        if ($user->isInternalAdmin()) {
            return true;
        } else if ($user->isCompanyAdmin()) {
            return $user->roleable->companiesCountWithWriteAccess() > 0;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        //
        if ($user->isInternalAdmin()) {
            return true;
        } else if ($user->isCompanyAdmin()) {
            return $user->canWriteCompany($company->id);
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Company $company): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        //
    }
}
