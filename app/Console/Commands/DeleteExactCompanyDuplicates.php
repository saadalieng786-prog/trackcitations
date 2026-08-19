<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeleteExactCompanyDuplicates extends Command
{
    protected $signature = 'companies:delete-exact-duplicates {--dry-run : Show count only, do not delete}';

    protected $description = 'Delete companies that match on all fields except id, created_at, and updated_at. Keeps the oldest id and moves related tickets/drivers onto it.';

    public function handle(): int
    {
        $this->info('Scanning companies...');

        $keep = [];
        $groups = [];

        Company::query()
            ->orderBy('id')
            ->chunkById(200, function ($companies) use (&$keep, &$groups) {
                foreach ($companies as $company) {
                    $hash = $this->rowHash($company);
                    $id = (int) $company->id;

                    if (! isset($keep[$hash])) {
                        $keep[$hash] = $id;
                        $groups[$hash] = [
                            'keep' => $id,
                            'name' => (string) ($company->name ?? ''),
                            'sf_id' => (string) ($company->sf_id ?? ''),
                            'delete' => [],
                        ];
                        continue;
                    }

                    $groups[$hash]['delete'][] = $id;
                }
            });

        $deleteMap = [];
        foreach ($groups as $group) {
            foreach ($group['delete'] as $id) {
                $deleteMap[$id] = $group['keep'];
            }
        }

        $count = count($deleteMap);
        $this->info("Duplicates to delete: {$count}");

        if ($count === 0) {
            $this->info('Nothing to delete.');

            return self::SUCCESS;
        }

        $shown = 0;
        foreach ($groups as $group) {
            if ($group['delete'] === []) {
                continue;
            }
            $this->line(sprintf(
                'Keep id %d (%s / sf_id %s) → delete %s',
                $group['keep'],
                $group['name'] !== '' ? $group['name'] : 'no name',
                $group['sf_id'] !== '' ? $group['sf_id'] : '-',
                implode(', ', $group['delete'])
            ));
            $shown++;
            if ($shown >= 30) {
                $this->line('...');
                break;
            }
        }

        if ($this->option('dry-run')) {
            $this->warn('Dry run only. No rows deleted. Run without --dry-run to delete.');

            return self::SUCCESS;
        }

        DB::statement('SET SESSION innodb_lock_wait_timeout = 5');

        $deleted = 0;
        $skipped = 0;

        foreach ($deleteMap as $dupId => $keepId) {
            $ok = false;

            for ($try = 1; $try <= 8; $try++) {
                try {
                    $this->mergeThenDelete($dupId, $keepId);
                    $ok = true;
                    $deleted++;
                    break;
                } catch (QueryException $e) {
                    if (! str_contains($e->getMessage(), '1205')) {
                        throw $e;
                    }
                    sleep(2);
                }
            }

            if (! $ok) {
                $skipped++;
                $this->warn("Skipped company id {$dupId} (still locked).");
            }
        }

        $this->info("Done. Deleted {$deleted} duplicate companies. Skipped {$skipped} locked rows.");

        return self::SUCCESS;
    }

    protected function mergeThenDelete(int $dupId, int $keepId): void
    {
        DB::transaction(function () use ($dupId, $keepId) {
            DB::table('tickets')->where('company_id', $dupId)->update(['company_id' => $keepId]);
            DB::table('drivers')->where('company_id', $dupId)->update(['company_id' => $keepId]);

            if (Schema::hasTable('company_contacts')) {
                DB::table('company_contacts')->where('company_id', $dupId)->update(['company_id' => $keepId]);
            }

            DB::table('companies')->where('parent_company_id', $dupId)->update(['parent_company_id' => $keepId]);

            if (Schema::hasTable('company_manager')) {
                $pivots = DB::table('company_manager')->where('company_id', $dupId)->get();
                foreach ($pivots as $pivot) {
                    $already = DB::table('company_manager')
                        ->where('company_id', $keepId)
                        ->where('manager_id', $pivot->manager_id)
                        ->exists();

                    if ($already) {
                        DB::table('company_manager')->where('id', $pivot->id)->delete();
                    } else {
                        DB::table('company_manager')->where('id', $pivot->id)->update(['company_id' => $keepId]);
                    }
                }
            }

            DB::table('companies')->where('id', $dupId)->delete();
        });
    }

    protected function rowHash(Company $company): string
    {
        $v = static fn ($value) => $value === null ? '' : (string) $value;

        return md5(implode('|', [
            $v($company->name),
            $v($company->parent_company_id),
            $v($company->ct_email),
            $v($company->ct_fname),
            $v($company->ct_lname),
            $v($company->dot),
            $v($company->sf_id),
        ]));
    }
}
