<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Filters;

use App\Filters\Filters;

class TicketFilters extends Filters
{
    protected $filters = ['q', 'name', 'status', 'attorney_id', 'company_id', 'court_date'];

    protected function q($q) {
        if (!empty($q)) {
            return $this->builder->where(function ($query) use ($q) {
                $query->where('id', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%")
                    ->orWhere('state', 'like', "%{$q}%")
                    ->orWhereHas('company', function ($c) use ($q) {
                        $c->where('name', 'like', "%{$q}%");
                    });
            });
        }
    }

    protected function name($name) {
        if ($name) {
            return $this->builder->whereLike('name', "%$name%");
        }
    }
    protected function status($status) {
        if (!is_null($status)) {
            return $this->builder->where('status', '=', $status);
        }
    }
    protected function court_date($courtDate) {
        if (!empty($courtDate)) {
            $dates = explode(' to ', $courtDate);
            $startDate = date($dates[0]);
            $endDate = date($dates[1]);
            return $this->builder->whereBetween('court_date', [$startDate, $endDate]);
        }
    }
    protected function company_id($company_id) {
        if ($company_id) {
            return $this->builder->where('company_id', $company_id);
        }
    }
    protected function attorney_id($attorney_id) {
        if ($attorney_id) {
            return $this->builder->where('attorney_id', $attorney_id);
        }
    }
}
