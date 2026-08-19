<?php

namespace App\Console\Commands;

use App\Models\MessageAttachment;
use App\Models\TicketAttachment;
use App\Support\AttachmentStorage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateStoredAttachments extends Command
{
    protected $signature = 'attachments:migrate
        {--from=public : Source disk}
        {--to=s3 : Target disk}
        {--type=all : ticket, message, or all}
        {--dry-run : Preview changes without copying files or updating the database}';

    protected $description = 'Copy stored attachments from one configured disk to another and update database URLs.';

    public function handle(): int
    {
        $fromDisk = (string) $this->option('from');
        $toDisk = (string) $this->option('to');
        $type = (string) $this->option('type');
        $dryRun = (bool) $this->option('dry-run');

        if (! in_array($fromDisk, ['public', 's3'], true) || ! in_array($toDisk, ['public', 's3'], true)) {
            $this->error('Only public and s3 disks are supported by this migration command.');
            return self::FAILURE;
        }

        if ($fromDisk === $toDisk) {
            $this->error('Source and target disks must be different.');
            return self::FAILURE;
        }

        if (! in_array($type, ['ticket', 'message', 'all'], true)) {
            $this->error('Type must be one of: ticket, message, all.');
            return self::FAILURE;
        }

        $this->info('Starting attachment migration...');
        $this->line('Source disk: '.$fromDisk);
        $this->line('Target disk: '.$toDisk);
        $this->line('Type: '.$type);
        $this->line('Dry run: '.($dryRun ? 'yes' : 'no'));

        $totals = [
            'checked' => 0,
            'copied' => 0,
            'updated' => 0,
            'missing' => 0,
            'failed' => 0,
            'skipped' => 0,
        ];

        if ($type === 'ticket' || $type === 'all') {
            $this->migrateTicketAttachments($fromDisk, $toDisk, $dryRun, $totals);
        }

        if ($type === 'message' || $type === 'all') {
            $this->migrateMessageAttachments($fromDisk, $toDisk, $dryRun, $totals);
        }

        $this->newLine();
        $this->info('Migration complete.');
        $this->table(
            ['Checked', 'Copied', 'Updated', 'Missing', 'Failed', 'Skipped'],
            [[
                $totals['checked'],
                $totals['copied'],
                $totals['updated'],
                $totals['missing'],
                $totals['failed'],
                $totals['skipped'],
            ]]
        );

        return self::SUCCESS;
    }

    protected function migrateTicketAttachments(string $fromDisk, string $toDisk, bool $dryRun, array &$totals): void
    {
        $this->newLine();
        $this->info('Migrating ticket attachments...');

        TicketAttachment::query()->chunkById(200, function ($attachments) use ($fromDisk, $toDisk, $dryRun, &$totals) {
            foreach ($attachments as $attachment) {
                $totals['checked']++;

                $relativePath = AttachmentStorage::relativePathFromStoredPath($attachment->path);
                if (! $relativePath) {
                    $totals['skipped']++;
                    continue;
                }

                if (! Storage::disk($fromDisk)->exists($relativePath)) {
                    if (Storage::disk($toDisk)->exists($relativePath)) {
                        if (! $dryRun) {
                            $attachment->update(['path' => $relativePath]);
                        }
                        $totals['updated']++;
                        continue;
                    }

                    $this->warn("Missing ticket attachment on {$fromDisk}: {$relativePath}");
                    $totals['missing']++;
                    continue;
                }

                try {
                    if (! $dryRun && ! Storage::disk($toDisk)->exists($relativePath)) {
                        $this->copyToDisk($fromDisk, $toDisk, $relativePath);
                        $totals['copied']++;
                    } elseif ($dryRun) {
                        $totals['copied']++;
                    }

                    if (! $dryRun) {
                        $attachment->update([
                            'path' => $relativePath,
                        ]);
                    }

                    $totals['updated']++;
                } catch (\Throwable $e) {
                    $this->error("Failed ticket attachment {$attachment->id}: {$e->getMessage()}");
                    $totals['failed']++;
                }
            }
        });
    }

    protected function migrateMessageAttachments(string $fromDisk, string $toDisk, bool $dryRun, array &$totals): void
    {
        $this->newLine();
        $this->info('Migrating message attachments...');

        MessageAttachment::query()->chunkById(200, function ($attachments) use ($fromDisk, $toDisk, $dryRun, &$totals) {
            foreach ($attachments as $attachment) {
                $totals['checked']++;

                $relativePath = AttachmentStorage::relativePathFromStoredPath($attachment->file_path);
                if (! $relativePath) {
                    $totals['skipped']++;
                    continue;
                }

                if (! Storage::disk($fromDisk)->exists($relativePath)) {
                    if (Storage::disk($toDisk)->exists($relativePath)) {
                        if (! $dryRun) {
                            $attachment->update(['file_path' => $relativePath]);
                        }
                        $totals['updated']++;
                        continue;
                    }

                    $this->warn("Missing message attachment on {$fromDisk}: {$relativePath}");
                    $totals['missing']++;
                    continue;
                }

                try {
                    if (! $dryRun && ! Storage::disk($toDisk)->exists($relativePath)) {
                        $this->copyToDisk($fromDisk, $toDisk, $relativePath);
                        $totals['copied']++;
                    } elseif ($dryRun) {
                        $totals['copied']++;
                    }

                    if (! $dryRun) {
                        $attachment->update([
                            'file_path' => $relativePath,
                        ]);
                    }

                    $totals['updated']++;
                } catch (\Throwable $e) {
                    $this->error("Failed message attachment {$attachment->id}: {$e->getMessage()}");
                    $totals['failed']++;
                }
            }
        });
    }

    protected function copyToDisk(string $fromDisk, string $toDisk, string $relativePath): void
    {
        $stream = Storage::disk($fromDisk)->readStream($relativePath);
        if ($stream === false) {
            throw new \RuntimeException('Unable to read '.$relativePath.' from '.$fromDisk);
        }

        try {
            $wrote = Storage::disk($toDisk)->put($relativePath, $stream, [
                'visibility' => $toDisk === 's3' ? 'private' : 'public',
            ]);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        if (! $wrote) {
            throw new \RuntimeException('Unable to write '.$relativePath.' to '.$toDisk);
        }
    }
}
