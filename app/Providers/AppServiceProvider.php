<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        if (str_starts_with((string) config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            // Return the name of the policy class for the given model...
            if ($modelClass === \App\Models\Ticket::class) {
                return \App\Policies\Policies\TicketPolicy::class;
            } elseif ($modelClass === \App\Models\Company::class) {
                return \App\Policies\Policies\CompanyPolicy::class;
            } elseif ($modelClass === \App\Models\Driver::class) {
                return \App\Policies\Policies\DriverPolicy::class;
            }
        });

    }
}
