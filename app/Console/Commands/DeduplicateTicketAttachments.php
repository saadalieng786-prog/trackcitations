<?php

namespace App\Console\Commands;

use App\Models\TicketAttachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeduplicateTicketAttachments extends Command
{
    protected $signature = 'attachments:dedupe-filenames
                            {--dry-run : List duplicates without deleting}
                            {--ticket= : Limit to a single ticket id}';

    protected $description = 'Remove duplicate ticket attachments that share the same filename on a ticket (keep newest).';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $ticketId = $this->option('ticket');

        $groups = TicketAttachment::query()
            ->select([
                'ticket_id',
                DB::raw('LOWER(TRIM(filename)) as name_key'),
                DB::raw('COUNT(*) as total'),
            ])
            ->when($ticketId, fn ($q) => $q->where('ticket_id', $ticketId))
            ->whereNotNull('filename')
            ->where('filename', '!=', '')
            ->groupBy('ticket_id', DB::raw('LOWER(TRIM(filename))'))
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($groups->isEmpty()) {
            $this->info('No filename duplicates found.');

            return self::SUCCESS;
        }

        $this->info('Found '.$groups->count().' duplicate filename group(s).');

        $deleted = 0;

        foreach ($groups as $group) {
            $rows = TicketAttachment::query()
                ->where('ticket_id', $group->ticket_id)
                ->whereRaw('LOWER(TRIM(filename)) = ?', [$group->name_key])
                ->orderByDesc('sf_last_modified_date')
                ->orderByDesc('id')
                ->get();

            $keep = $rows->first();
            $remove = $rows->slice(1);

            $this->line(sprintf(
                'Ticket #%s · %s · keep id %s · remove %s',
                $group->ticket_id,
                $keep->filename,
                $keep->id,
                $remove->pluck('id')->implode(', ')
            ));

            if ($dryRun) {
                continue;
            }

            $deleted += TicketAttachment::query()
                ->whereIn('id', $remove->pluck('id'))
                ->delete();
        }

        if ($dryRun) {
            $this->comment('Dry run only — no rows deleted.');
        } else {
            $this->info("Deleted {$deleted} duplicate attachment row(s).");
        }

        return self::SUCCESS;
    }
}
