<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Policies\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->isInternalAdmin()) {
            return true;
        } else if ($user->isCompanyAdmin()) {
            return $user->canAccessCompany($ticket->company_id);
        } else if ($user->hasRole(User::ROLE_ATTORNEY)) {
            return $ticket->attorney_id === $user->roleable->id;
        } else if ($user->hasRole(User::ROLE_DRIVER)) {
            return $ticket->driver?->user?->id === $user->id;
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
            $companiesWithWriteAccessCount = $user->roleable->companiesCountWithWriteAccess();
           return $companiesWithWriteAccessCount > 0;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        //
        if ($user->isInternalAdmin()) {
            return true;
        } else if ($user->isCompanyAdmin()) {
            return $user->canWriteCompany($ticket->company_id);
        } else if ($user->hasRole(User::ROLE_ATTORNEY)) {
            return $user->roleable->id === $ticket->attorney_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        //
        if ($user->isInternalAdmin()) {
            return true;
        } else if ($user->isCompanyAdmin()) {
            return $user->canWriteCompany($ticket->company_id);
        }
        return false;
    }

}
