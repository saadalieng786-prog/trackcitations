<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckManagerAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $manager = Auth::user();
        $companyId = (int) $request->route('company_id');

        // Check if the user is a company account and can access the requested company.
        if (! $manager instanceof User || ! $manager->isCompanyAdmin() || ! $manager->canAccessCompany($companyId)) {
            abort(403, 'Unauthorized access to this company.');
        }

        return $next($request);
    }
}
