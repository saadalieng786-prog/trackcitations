<?php

namespace App\Console\Commands;

use App\Models\TicketAttachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Support\AttachmentStorage;

class FixAttachmentTrailingDots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salesforce:fix-attachment-dots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix attachments that have a trailing dot in their filename and path';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding attachments with trailing dots in filename...');

        $query = TicketAttachment::where('filename', 'like', '%.');
        $count = $query->count();

        if ($count === 0) {
            $this->info('No attachments found with a trailing dot.');
            return;
        }

        $this->info('Found ' . $count . ' attachments. Processing...');

        $fixedCount = 0;
        $notFoundCount = 0;
        $disk = AttachmentStorage::ticketDisk();

        $query->chunkById(500, function ($attachments) use ($disk, &$fixedCount, &$notFoundCount) {
            foreach ($attachments as $attachment) {
            $oldFilename = $attachment->filename;
            $newFilename = rtrim($oldFilename, '.');
            
            $oldPathUrl = $attachment->path;
            
            // If the path URL ends with a dot, try to rename the physical file locally
            if (str_ends_with($oldPathUrl, '.')) {
                $oldRelativePath = AttachmentStorage::relativePathFromStoredPath($oldPathUrl);
                
                if ($oldRelativePath && Storage::disk($disk)->exists($oldRelativePath)) {
                    $newRelativePath = substr($oldRelativePath, 0, -1); // remove the dot
                    Storage::disk($disk)->move($oldRelativePath, $newRelativePath);
                    $attachment->path = Storage::disk($disk)->url($newRelativePath);
                } else {
                    $this->warn("File ends with dot but not found on configured attachment disk: {$oldRelativePath}");
                    $notFoundCount++;
                }
            }

            $attachment->filename = $newFilename;
            $attachment->save();
            
            $this->line("Fixed: {$oldFilename} -> {$newFilename}");
            $fixedCount++;
        }
        });

        $this->info("Completed. Fixed: {$fixedCount}, Not Found: {$notFoundCount}.");
    }
}
