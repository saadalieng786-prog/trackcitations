<?php

namespace App\Console\Commands;

use App\Integrations\Salesforce\SalesforceClient;
use App\Integrations\Salesforce\SalesforceService;
use App\Models\SalesForce;
use App\Models\TicketAttachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Support\AttachmentStorage;

class FixZeroByteAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salesforce:fix-zero-byte';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds and re-downloads any local attachments that are 0 bytes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting scan for 0 byte attachments...');

        $salesForce = SalesForce::first();
        if (!$salesForce) {
            $this->error('Salesforce configuration not found.');
            return;
        }

        $sf = new SalesforceService(
            $salesForce->sf_instance_url,
            $salesForce->sf_access_token
        );
        $client = new SalesforceClient();

        $disk = AttachmentStorage::ticketDisk();
        $query = TicketAttachment::whereNotNull('sf_id');
        $totalChecked = 0;
        $totalFixed = 0;
        $totalFailed = 0;

        $query->chunkById(500, function ($attachments) use ($sf, $client, $disk, &$totalChecked, &$totalFixed, &$totalFailed) {
            foreach ($attachments as $attachment) {
                $totalChecked++;
                
                $relativePath = AttachmentStorage::relativePathFromStoredPath($attachment->path);

                if (!$relativePath || !Storage::disk($disk)->exists($relativePath)) {
                    continue; // Might be old data or completely missing (if we want to recover missing we can change this)
                }

                $size = Storage::disk($disk)->size($relativePath);

                if ($size === 0) {
                    $this->info("Zero byte file found: {$relativePath}. Re-downloading...");

                    // Endpoint to fetch the raw attachment body directly via ID
                    if (str_starts_with($attachment->sf_id, '068')) {
                        // For ContentVersion, we might need to check if ContentUrl exists if it's 0 bytes, 
                        // but doing a simple SOQL query to get the url is required if it's an external file.
                        // Let's retrieve ContentUrl or VersionData
                        $query = "SELECT ContentSize, ContentUrl, VersionData FROM ContentVersion WHERE Id = '{$attachment->sf_id}'";
                        $result = $sf->apiCall('/services/data/v58.0/query', ['q' => $query]);
                        $cv = $result['records'][0] ?? null;
                        
                        if ($cv) {
                            $bodyUrl = $cv['VersionData'];
                            if (empty($cv['ContentSize']) && !empty($cv['ContentUrl'])) {
                                $bodyUrl = $cv['ContentUrl'];
                            }
                        } else {
                            $this->error("ContentVersion {$attachment->sf_id} not found.");
                            continue;
                        }
                    } else {
                        $bodyUrl = "/services/data/v58.0/sobjects/Attachment/{$attachment->sf_id}/Body";
                    }
                    $sinkPath = AttachmentStorage::isLocalDisk($disk)
                        ? Storage::disk($disk)->path($relativePath)
                        : null;

                    try {
                        $response = $sf->getFile($bodyUrl, 0, $sinkPath);
                        
                        // Handle Token Expirations or Rate Limits explicitly 
                        if (is_array($response)) {
                            Log::warning("Token likely expired during 0-byte sync, reconnecting...");
                            $client->connect();
                            $response = $sf->getFile($bodyUrl, 0, $sinkPath);
                            if (is_array($response)) {
                                $this->error("Failed to re-download {$relativePath} after reconnect.");
                                $totalFailed++;
                                continue;
                            }
                        }

                        if ($response && $response->ok()) {
                            if (! AttachmentStorage::isLocalDisk($disk)) {
                                AttachmentStorage::storeSalesforceContents($relativePath, $response->body());
                            }

                            $newSize = Storage::disk($disk)->size($relativePath);
                            if ($newSize > 0) {
                                $this->line("<info>Successfully restored:</info> {$relativePath} ({$newSize} bytes)");
                                $totalFixed++;
                            } else {
                                $this->error("Re-downloaded content is still 0 bytes for {$relativePath}!");
                                $totalFailed++;
                            }
                        } else {
                            $this->error("API returned failure status for {$relativePath}.");
                            $totalFailed++;
                        }
                    } catch (\Exception $e) {
                        $this->error("Exception while fetching {$relativePath}: " . $e->getMessage());
                        $totalFailed++;
                    }
                }
            }
        });

        $this->info("Finished scan! Checked: {$totalChecked} | Fixed: {$totalFixed} | Failed: {$totalFailed}");
    }
}
