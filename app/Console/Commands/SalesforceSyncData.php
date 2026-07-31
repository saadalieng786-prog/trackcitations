<?php

namespace App\Console\Commands;

use App\Integrations\Salesforce\SalesforceService;
use App\Integrations\Salesforce\SalesforceSyncService;
use App\Models\SalesForce;
use Illuminate\Console\Command;

class SalesforceSyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salesforce:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Salesforce Sync Script';

    /**
     * Execute the console command.
     */
    public function handle(SalesforceSyncService $sfSyncService)
    {
        //
        try {

            $salesForce = SalesForce::first();

            $salesForce->update(['status' => SalesForce::STATUS_RUNNING, 'reason' => '']);

            $sf = new \App\Integrations\Salesforce\SalesforceService(
                $salesForce->sf_instance_url,
                $salesForce->sf_access_token
            );

            $sf->resetSFConnection();

            $this->info('Run Salesforce Sync Script');

            $records = $sf->fetchContacts();

            if($records['totalSize'] > 0) {
                $sfSyncService->sync($records['records']);
                // rarely when records more than 2000
                while(isset($records['nextRecordsUrl']) && $records['nextRecordsUrl']!='') {
                    $records = $sf->apiCall($records['nextRecordsUrl']);
                    $sfSyncService->sync($records['records']);
                }
            }

            $attachments =  $sf->fetchAttachments();

            if($attachments['totalSize'] > 0 && !empty($attachments['records'])) {
                $ids = $sf->getAttachmentIds($attachments['records']);

                $sfSyncService->syncAttachments($ids[0], $ids[1], $sf);
                // rarely when records more than 2000
                while(isset($attachments['nextRecordsUrl']) && $attachments['nextRecordsUrl']!='') {
                    $attachments = $sf->apiCall($attachments['nextRecordsUrl']);
                    $ids = $sf->getAttachmentIds($attachments['records']);
                    $sfSyncService->syncAttachments($ids[0], $ids[1], $sf);
                }
            }

            $contentVersions = $sf->fetchContentVersions();

            if($contentVersions['totalSize'] > 0 && !empty($contentVersions['records'])) {
                $ids = $sf->getAttachmentIds($contentVersions['records']);

                $sfSyncService->syncAttachments($ids[0], $ids[1], $sf);
                // rarely when records more than 2000
                while(isset($contentVersions['nextRecordsUrl']) && $contentVersions['nextRecordsUrl']!='') {
                    $contentVersions = $sf->apiCall($contentVersions['nextRecordsUrl']);
                    $contentVersions = $sf->mapContentVersionsToAttachmentsFormat($contentVersions, $sf);
                    $ids = $sf->getAttachmentIds($contentVersions['records']);
                    $sfSyncService->syncAttachments($ids[0], $ids[1], $sf);
                }
            }
            $salesForce->update(['status' => SalesForce::STATUS_FINISHED]);
        } catch (\Exception $e) {
            $salesForce->update(['status' => SalesForce::STATUS_FAILED, 'reason' => $e->getMessage()]);
        }

    }
}
