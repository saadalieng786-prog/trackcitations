<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateManagersToCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:create-managers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create managers for companies that don\'t have any managers assigned';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Fetch all companies without managers
        $companies = Company::doesntHave('managers')->get();

        if ($companies->isEmpty()) {
            $this->info('✅ All companies already have managers.');
            return;
        }

        foreach ($companies as $company) {
            // Skip if company email is missing
            if (empty($company->ct_email)) {
                $this->warn("⚠️ Company {$company->id} ({$company->name}) has no email. Skipped.");
                continue;
            }

            // Check if a user with this email already exists
            $existingUser = \App\Models\User::where('email', $company->ct_email)->first();

            if ($existingUser) {
                // Check if this user is already a Manager
                if ($existingUser->roleable_type === Manager::class) {
                    $manager = $existingUser->roleable;

                    $this->info("ℹ️ User with email {$company->ct_email} already exists as Manager (ID {$manager->id}).");
                } else {
                    $this->warn("⚠️ User with email {$company->ct_email} exists but is not a Manager. Skipped.");
                    continue;
                }
            } else {
                // Create fresh manager + user
                $manager = Manager::create([]);

                $user = $manager->user()->create([
                    'name'     => $company->name,
                    'email'    => $company->ct_email,
                    'password' => \Illuminate\Support\Facades\Hash::make($company->ct_email),
                ]);

                $user->assignRole(User::ROLE_COMPANY_ADMIN);

                $this->info("👤 Manager created for Company: {$company->name} ({$company->ct_email})");
            }

            // Attach manager to company (if not already attached)
            if (!$manager->companies->contains($company->id)) {
                $manager->companies()->attach($company->id, [
                    'is_write_access' => 1
                ]);
                $this->line("✅ Linked Company {$company->name} to Manager ID {$manager->id}");
            } else {
                $this->line("➡️ Company {$company->name} already linked to Manager ID {$manager->id}");
            }
        }

        $this->info('🎉 All managers assigned successfully.');
    }

}
