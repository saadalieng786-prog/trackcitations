<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Filters;

class UserFilters extends Filters
{
    protected $filters = ['name', 'role'];

    protected function name($name) {
        return $this->builder->whereLike('name', "%$name%");
    }

    protected function role($role) {
        $this->builder->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role);
        });
    }
}
