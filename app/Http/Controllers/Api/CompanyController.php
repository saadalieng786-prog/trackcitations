<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index() {
        $user = request()->user();
        if ($user->isInternalAdmin()) {
            return Company::all();
        } else if ($user->isCompanyAdmin()) {
            return Company::whereIn('id', $user->managedCompanyIds())->get();
        }

        return collect();
    }
}
