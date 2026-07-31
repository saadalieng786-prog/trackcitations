<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    //
    public function user() {
        return $this->morphOne(User::class, 'roleable');
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('is_write_access')
            ->withTimestamps();
    }

    public function companyPermissions()
    {
        return $this->hasMany(CompanyManagerPermission::class, 'manager_id');
    }

    public function companiesCountWithWriteAccess()
    {
        return count($this->user?->writableCompanyIds() ?? []);
    }

    public function canWriteToCompany($company_id) {
        return (bool) $this->user?->canWriteCompany((int) $company_id);
    }

    public function canWriteToOtherManagerCompany(Manager $manager) {
        // Get companies managed by the current manager
        $managerCompanies = auth()->user()
            ->roleable
            ->companies()
            ->wherePivot('is_write_access', true)
            ->get();

        // Get companies managed by the other manager
        $otherManagerCompanies = $manager->companies()->get();

        // Get the intersection of both companies (shared companies)
        $sharedCompanies = $managerCompanies->intersect($otherManagerCompanies);

        return $sharedCompanies->count() > 0;
    }
}
