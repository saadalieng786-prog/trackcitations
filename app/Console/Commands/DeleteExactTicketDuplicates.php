<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class DeleteExactTicketDuplicates extends Command
{
    protected $signature = 'tickets:delete-exact-duplicates
        {--dry-run : Show count only, do not delete}
        {--show-locks : List MySQL connections that may be locking tickets}
        {--kill= : Kill a MySQL connection id from --show-locks}';

    protected $description = 'Delete tickets that match on all fields except id, created_at, and updated_at. Keeps the oldest id.';

    public function handle(): int
    {
        if ($this->option('show-locks')) {
            return $this->showLocks();
        }

        if ($killId = $this->option('kill')) {
            DB::unprepared('KILL '.(int) $killId);
            $this->info("Killed MySQL connection {$killId}.");

            return self::SUCCESS;
        }

        $this->info('Scanning tickets...');

        $keep = [];
        $delete = [];

        Ticket::withoutGlobalScopes()
            ->orderBy('id')
            ->chunkById(500, function ($tickets) use (&$keep, &$delete) {
                foreach ($tickets as $ticket) {
                    $hash = $this->rowHash($ticket);
                    if (! isset($keep[$hash])) {
                        $keep[$hash] = (int) $ticket->id;
                        continue;
                    }
                    $delete[] = (int) $ticket->id;
                }
            });

        $delete = array_values(array_unique($delete));
        $count = count($delete);

        $this->info("Duplicates to delete: {$count}");

        if ($count === 0) {
            $this->info('Nothing to delete.');

            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->warn('Dry run only. No rows deleted. Run without --dry-run to delete.');
            $this->line('First 20 ids: '.implode(', ', array_slice($delete, 0, 20)));

            return self::SUCCESS;
        }

        DB::statement('SET SESSION innodb_lock_wait_timeout = 5');

        $deleted = 0;
        $skipped = 0;

        foreach ($delete as $id) {
            $ok = false;

            for ($try = 1; $try <= 8; $try++) {
                try {
                    $deleted += DB::table('tickets')->where('id', $id)->delete();
                    $ok = true;
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
                $this->warn("Skipped id {$id} (still locked).");
            }

            if (($deleted + $skipped) % 50 === 0) {
                $this->line("Deleted {$deleted} / {$count} (skipped {$skipped})");
            }
        }

        $this->info("Done. Deleted {$deleted} duplicate tickets. Skipped {$skipped} locked rows.");

        return self::SUCCESS;
    }

    protected function rowHash(Ticket $ticket): string
    {
        $v = static fn ($value) => $value === null ? '' : (string) $value;

        return md5(implode('|', [
            $v($ticket->name),
            $v($ticket->company_id),
            $v($ticket->address),
            $v($ticket->birthdate),
            $v($ticket->city),
            $v($ticket->state),
            $v($ticket->zip),
            $v($ticket->dl_number),
            $v($ticket->class_commercial),
            $v($ticket->vehicle_lic_no),
            $v($ticket->citation_type),
            $v($ticket->violation_id),
            $v($ticket->location_violation),
            $v($ticket->city_county_occurrence),
            $v($ticket->speed_approx),
            $v($ticket->arresting_officer_name),
            $v($ticket->note),
            $v($ticket->file),
            $v($ticket->path),
            $v($ticket->date_time),
            $v($ticket->user_email),
            $v($ticket->phone),
            $v($ticket->indicator),
            $v($ticket->disposition__c),
            $v($ticket->confirmed__c),
            $v($ticket->canceled__c),
            $v($ticket->lawyer_email),
            $v($ticket->admin_note),
            $v($ticket->citation_no),
            $v($ticket->status),
            $v($ticket->updated_by),
            $v($ticket->court_date),
            $v($ticket->court_address),
            $v($ticket->court_phone),
            $v($ticket->ticket_dispo),
            $v($ticket->date_issued),
            $v($ticket->court_name),
            $v($ticket->county),
            $v($ticket->ticket_number),
            $v($ticket->attorney_id),
            $v($ticket->road_side_inspection),
            $v($ticket->road_side_inspection_results),
            $v($ticket->sales_agent),
            $v($ticket->fname),
            $v($ticket->lname),
            $v($ticket->sales_agent_name),
            $v($ticket->sales_agent_email),
            $v($ticket->sales_agent_id),
            $v($ticket->sf_id),
            $v($ticket->dataq_number__c),
            $v($ticket->roadside_inspection_number__c),
            $v($ticket->ticket_type),
            $v($ticket->beginning_fine_amount),
            $v($ticket->final_fine_amount),
            $v($ticket->processor_name),
            $v($ticket->processor_email),
            $v($ticket->processor_ph_number),
            $v($ticket->processor_notes_to_attorney),
            $v($ticket->total_dver_points__c),
            $v($ticket->total_dver_points_removed__c),
            $v($ticket->attorney_response),
            $v($ticket->is_approved),
        ]));
    }

    protected function showLocks(): int
    {
        $rows = DB::select('SHOW FULL PROCESSLIST');

        foreach ($rows as $row) {
            $info = trim((string) ($row->Info ?? ''));
            if ($info === '') {
                $info = '-';
            }

            $this->line(sprintf(
                'Id=%s  Time=%ss  Command=%s  State=%s  Info=%s',
                $row->Id,
                $row->Time,
                $row->Command,
                $row->State ?? '',
                substr($info, 0, 300)
            ));
        }

        $this->newLine();
        $this->warn('Kill any row whose Info is DELETE/UPDATE on tickets, or Command=Sleep with a high Time.');
        $this->line('Example: php artisan tickets:delete-exact-duplicates --kill=123');

        return self::SUCCESS;
    }
}
