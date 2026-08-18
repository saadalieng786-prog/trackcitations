<?php

namespace App\Console\Commands;

use App\Integrations\Salesforce\SalesforceService;
use App\Integrations\Salesforce\SalesforceSyncLogger;
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
        $salesForce = SalesForce::first();

        if (! $salesForce) {
            $this->error('Salesforce settings row not found.');
            SalesforceSyncLogger::error('Salesforce settings row not found.');

            return self::FAILURE;
        }

        try {
            SalesforceSyncLogger::clear();
            SalesforceSyncLogger::info('Salesforce sync started', [
                'has_instance' => filled($salesForce->sf_instance_url),
                'has_access_token' => filled($salesForce->sf_access_token),
                'has_refresh_token' => filled($salesForce->sf_refresh_token),
                'login_uri_set' => filled($salesForce->login_uri),
            ]);

            $salesForce->update(['status' => SalesForce::STATUS_RUNNING, 'reason' => '']);

            $sf = new SalesforceService(
                $salesForce->sf_instance_url,
                $salesForce->sf_access_token
            );

            SalesforceSyncLogger::info('Refreshing Salesforce connection/token...');
            $sf->resetSFConnection();
            SalesforceSyncLogger::info('Connection refresh completed');

            $this->info('Run Salesforce Sync Script');

            SalesforceSyncLogger::info('Fetching contacts (Account.Export__c = TRUE)...');
            $records = $sf->fetchContacts();

            if (isset($records['error']) || isset($records['errorCode'])) {
                SalesforceSyncLogger::error('Contact fetch failed', [
                    'status' => $records['status'] ?? null,
                    'message' => $records['message'] ?? $records,
                ]);
                throw new \RuntimeException('Contact fetch failed: '.($records['message'] ?? json_encode($records)));
            }

            $contactCount = (int) ($records['totalSize'] ?? 0);
            SalesforceSyncLogger::info('Contacts fetched', [
                'totalSize' => $contactCount,
                'page_records' => count($records['records'] ?? []),
                'has_next' => ! empty($records['nextRecordsUrl']),
            ]);

            if (! empty($records['records'])) {
                foreach (array_slice($records['records'], 0, 20) as $index => $record) {
                    SalesforceSyncLogger::info('Contact sample', [
                        'index' => $index,
                        'contact_id' => $record['Id'] ?? null,
                        'account_id' => $record['Account']['Id'] ?? null,
                        'account_name' => $record['Account']['Name'] ?? null,
                        'email' => $record['Email'] ?? null,
                        'name' => trim(($record['FirstName'] ?? '').' '.($record['LastName'] ?? '')),
                    ]);
                }
            }

            if ($contactCount > 0) {
                $sfSyncService->sync($records['records'] ?? []);
                SalesforceSyncLogger::info('Synced first contacts page');

                while (isset($records['nextRecordsUrl']) && $records['nextRecordsUrl'] != '') {
                    $records = $sf->apiCall($records['nextRecordsUrl']);
                    $sfSyncService->sync($records['records'] ?? []);
                    SalesforceSyncLogger::info('Synced contacts next page', [
                        'page_records' => count($records['records'] ?? []),
                    ]);
                }
            } else {
                SalesforceSyncLogger::info('No contacts returned. Check Export__c=true and that the authorized Salesforce user can see the Account/Contact.');
            }

            SalesforceSyncLogger::info('Fetching legacy attachments...');
            $attachments = $sf->fetchAttachments();
            $attachmentCount = (int) ($attachments['totalSize'] ?? 0);
            SalesforceSyncLogger::info('Attachments fetched', ['totalSize' => $attachmentCount]);

            if ($attachmentCount > 0 && ! empty($attachments['records'])) {
                $ids = $sf->getAttachmentIds($attachments['records']);
                $sfSyncService->syncAttachments($ids[0], $ids[1], $sf);

                while (isset($attachments['nextRecordsUrl']) && $attachments['nextRecordsUrl'] != '') {
                    $attachments = $sf->apiCall($attachments['nextRecordsUrl']);
                    $ids = $sf->getAttachmentIds($attachments['records']);
                    $sfSyncService->syncAttachments($ids[0], $ids[1], $sf);
                }
            }

            SalesforceSyncLogger::info('Fetching ContentVersion files...');
            $contentVersions = $sf->fetchContentVersions();
            $fileCount = (int) ($contentVersions['totalSize'] ?? 0);
            SalesforceSyncLogger::info('ContentVersion files fetched', ['totalSize' => $fileCount]);

            if ($fileCount > 0 && ! empty($contentVersions['records'])) {
                $ids = $sf->getAttachmentIds($contentVersions['records']);
                $sfSyncService->syncAttachments($ids[0], $ids[1], $sf);

                while (isset($contentVersions['nextRecordsUrl']) && $contentVersions['nextRecordsUrl'] != '') {
                    $contentVersions = $sf->apiCall($contentVersions['nextRecordsUrl']);
                    $contentVersions = $sf->mapContentVersionsToAttachmentsFormat($contentVersions, $sf);
                    $ids = $sf->getAttachmentIds($contentVersions['records']);
                    $sfSyncService->syncAttachments($ids[0], $ids[1], $sf);
                }
            }

            $salesForce->refresh();
            if ((int) $salesForce->status === SalesForce::STATUS_FAILED) {
                SalesforceSyncLogger::error('Salesforce sync finished with record errors', [
                    'reason' => $salesForce->reason,
                    'contacts' => $contactCount,
                    'attachments' => $attachmentCount,
                    'files' => $fileCount,
                ]);

                return self::FAILURE;
            }

            $salesForce->update(['status' => SalesForce::STATUS_FINISHED]);
            SalesforceSyncLogger::info('Salesforce sync finished successfully', [
                'contacts' => $contactCount,
                'attachments' => $attachmentCount,
                'files' => $fileCount,
            ]);

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $salesForce->update(['status' => SalesForce::STATUS_FAILED, 'reason' => $e->getMessage()]);
            SalesforceSyncLogger::error('Salesforce sync failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
