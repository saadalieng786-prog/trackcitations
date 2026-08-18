<?php

namespace App\Integrations\Salesforce;

use App\Models\SalesForce;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Output\ConsoleOutput;

class SalesforceService
{
    protected string $instanceUrl;
    protected string $accessToken;

    public function __construct(string $instanceUrl, string $accessToken, private ConsoleOutput $output = new ConsoleOutput(), private SalesforceClient $client = new SalesforceClient())
    {
        $this->instanceUrl = $instanceUrl;
        $this->accessToken = $accessToken;
    }

    protected function line(string $message): void
    {
        if (PHP_SAPI === 'cli') {
            $this->output->writeln($message);

            return;
        }

        SalesforceSyncLogger::info(trim(strip_tags($message)));
    }

    public function apiCall(string $endpoint, array $query = [], $tries = 0): array
    {
        $this->line('<info>[Salesforce] Starting API call to: ' . $endpoint . '</info>');

        $url = rtrim($this->instanceUrl, '/') . '/' . ltrim($endpoint, '/');

        try {
            $response = Http::withToken($this->accessToken)
                ->get($url, $query);

            if ($response->successful()) {
                $this->line('<info>[Salesforce] API call success.</info>');
                return $response->json();
            } else {

                if ($response->status() === 401) {
                    $this->line('<info>[Salesforce] Getting New Token ( ' . $tries . ' )</info>');

                    if ($tries < config('services.salesforce.timeout_max_tries')) {
                        $this->client->connect();
                        $salesForce = SalesForce::first();
                        $this->accessToken = $salesForce->sf_access_token;
                        return $this->apiCall($endpoint, $query, $tries + 1);
                    }
                }

                $message = '<error>[Salesforce] API error: ' . $response->status() . ' ' . $response->body() . '</error>';
                SalesForce::first()->update(['status' => SalesForce::STATUS_FAILED, 'reason' => $message]);

                $this->line($message);
                return [
                    'error' => true,
                    'status' => $response->status(),
                    'message' => $response->body(),
                ];
            }
        } catch (\Exception $e) {
            $this->line('<error>[Salesforce] Exception</error>');
            $this->line('<info>[Salesforce] Getting New Token ( ' . $tries . ' )</info>');

            if ($tries < config('services.salesforce.timeout_max_tries')) {
                $this->client->connect();
                $salesForce = SalesForce::first();
                $this->accessToken = $salesForce->sf_access_token;
                return $this->apiCall($endpoint, $query, $tries + 1);
            }
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function resetSFConnection(): void
    {
        $this->client->connect();
    }

    public function fetchContacts($params = [])
    {
        $salesForce = SalesForce::first();

        $sf = new \App\Integrations\Salesforce\SalesforceService(
            $salesForce->sf_instance_url,
            $salesForce->sf_access_token
        );

        $last2Years = Carbon::now()
            ->subYears(2)
            ->startOfDay()
            ->format('Y-m-d\TH:i:s.000+0000');


        $query = "
            SELECT
                Account.Id,
                Account.Name,
                Account.Phone,
                Account.Text_Phone__c,
                Email,
                MobilePhone,
                Phone,
                Account.Primary_Contact_Name__c,
                Account.Contact_Email__c,
                Account.Primary_Contact_Email__c,
                Account.Alternate_Email__c,
                Account.LastModifiedDate,
                Account.SystemModstamp,
                Account.ParentId,
                Account.Citation_Tracker_User_Email__c,
                Account.Citation_Tracker_User_First_Name__c,
                Account.Citation_Tracker_User_Last_Name__c,
                Account.DOT_Number__c,
                Company_Name__c,
                Company_Contact_Email__c,
                Account.BillingStreet,
                Account.BillingCity,
                Account.BillingState,
                Account.BillingPostalCode,
                Account.BillingCountry,
                Company_Phone_for_Text_Alerts__c,
                MailingStreet,
                MailingCity,
                MailingState,
                MailingPostalCode,
                MailingCountry,
                Secondary_Company_Email__c,
                Company_Text_Alerts__c,
                Citation_Type__c,
                Ticket_State__c,
                Date_Issued__c,
                Court_Date__c,
                Ticket_Number__c,
                Court_Name__c,
                Court_Address__c,
                County__c,
                Court_Phone_Number__c,
                Dispo__c,
                Roadside_Inspection__c,
                Sales_Agent__c,
                FirstName,
                LastName,
                Name,
                Id,
                Driver_Address__c,
                Driver_City__c,
                Driver_State__c,
                Driver_Zip_Code__c,
                Attorney__c,
                Attorney_Address__c,
                Attorney_Number__c,
                Attorney_Email_Address__c,
                LastModifiedDate,
                SystemModstamp,
                Account.Sales_Agent_Email__c,
                Account.Sales_Agent_Name__c,
                Account.Sales_Agent_Phone__c,
                Account.Sales_Agent_Text_Phone__c,
                DataQ_Number__c,
                Total_DVER_Points__c,
                Total_DVER_Points_REMOVED__c,
                Roadside_Inspection_Number__c,
                Ticket_Type__c,
                Beginning_Fine_Amount__c,
                Final_Fine_Amount__c,
                Processor_Notes_To_Attorney__c,
                Processor_Ph_Number__c,
                Processor_Name__c,
                Processor_Email__c,
                Disposition__c,
                Confirmed__c,
                Canceled__c,
                Attorney_response__c,
                Attorney_City__c,
                Attorney_State__c,
                Attorney_Zip__c,
                Court_City__c,
                Court_County__c,
                Court_Email_Address__c,
                Court_Zip__c,
                Date_of_Birth__c,
                Response_Dispo__c,
                Dispo_Results_From_Attorney__c
            FROM Contact
            WHERE Account.Export__c = TRUE
            ";

        if ($params) {
            if (isset($params['__email_or__'])) {
                $safeEmail = $params['__email_or__'];
                $query .= " AND (
                    Email = '{$safeEmail}'
                    OR Account.Primary_Contact_Email__c = '{$safeEmail}'
                    OR Account.Contact_Email__c = '{$safeEmail}'
                    OR Account.Citation_Tracker_User_Email__c = '{$safeEmail}'
                    OR Account.Alternate_Email__c = '{$safeEmail}'
                )";
            } else {
                foreach ($params as $field => $value) {
                    $safeValue = addslashes($value);
                    $query .= " AND {$field} = '{$safeValue}'";
                }
            }
        } else {
            if (
                $salesForce->sf_last_sync_time !== '' &&
                $salesForce->sf_last_sync_time !== null &&
                $salesForce->sf_last_sync_time !== '0' ) {
                $query .= "AND (CreatedDate > {$last2Years} AND SystemModstamp > {$salesForce->sf_last_sync_time})";
            } else {
                $query .= "AND CreatedDate > {$last2Years}";
            }
        }

        $query .= " ORDER BY SystemModstamp ASC";

        $result = $sf->apiCall('/services/data/v58.0/query', ['q' => $query]);

        return $result;
    }

    public function fetchContactsByEmail(string $email)
    {
        $safeEmail = addslashes($email);

        return $this->fetchContacts([
            '__email_or__' => $safeEmail,
        ]);
    }

    public function fetchAttachments()
    {
        $salesForce = SalesForce::first();
        $sf = new \App\Integrations\Salesforce\SalesforceService(
            $salesForce->sf_instance_url,
            $salesForce->sf_access_token
        );

        $last2Years = Carbon::now()
            ->subYears(2)
            ->startOfDay()
            ->format('Y-m-d\TH:i:s.000+0000');

        $query = "SELECT Body, BodyLength, Description, Id, LastModifiedDate, CreatedDate, Name, ParentId
          FROM Attachment ";

        if ($salesForce->sf_att_last_sync_time) {
            $query .= "WHERE CreatedDate > {$salesForce->sf_att_last_sync_time}";
        } else {
            $query .= "WHERE CreatedDate > {$last2Years}";

        }
        $query .= " ORDER BY CreatedDate ASC";

        $result = $sf->apiCall('/services/data/v58.0/query', ['q' => $query]);
        return $result;
    }

    public function fetchContentVersions()
    {
        $salesForce = SalesForce::first();
        $sf = new \App\Integrations\Salesforce\SalesforceService(
            $salesForce->sf_instance_url,
            $salesForce->sf_access_token
        );

        $last2Years = Carbon::now()
            ->subYears(2)
            ->startOfDay()
            ->format('Y-m-d\TH:i:s.000+0000');

        $query = "SELECT VersionData, ContentSize, ContentUrl, Description, Id, LastModifiedDate, CreatedDate, Title, ContentDocumentId
          FROM ContentVersion ";

        if ($salesForce->sf_file_last_sync_time && $salesForce->sf_file_last_sync_time !== '0') {
            $query .= "WHERE CreatedDate > {$salesForce->sf_file_last_sync_time} AND IsLatest = true";
        } else if ($salesForce->sf_att_last_sync_time) {
            // Fallback to legacy sync time if file sync time is empty to not resync historical unless needed
            $query .= "WHERE CreatedDate > {$salesForce->sf_att_last_sync_time} AND IsLatest = true";
        } else {
            $query .= "WHERE CreatedDate > {$last2Years} AND IsLatest = true";
        }
        $query .= " ORDER BY CreatedDate ASC";

        $result = $sf->apiCall('/services/data/v58.0/query', ['q' => $query]);
        return $this->mapContentVersionsToAttachmentsFormat($result, $sf);
    }

    public function mapContentVersionsToAttachmentsFormat($result, $sf)
    {
        if (empty($result['records'])) {
            return $result;
        }

        $docIds = array_filter(array_unique(array_column($result['records'], 'ContentDocumentId')));
        
        $links = [];
        if (!empty($docIds)) {
            $docIdChunks = array_chunk($docIds, 200);
            foreach ($docIdChunks as $chunk) {
                $cdlQuery = "SELECT ContentDocumentId, LinkedEntityId FROM ContentDocumentLink WHERE ContentDocumentId IN ('" . implode("','", $chunk) . "')";
                $cdlResult = $sf->apiCall('/services/data/v58.0/query', ['q' => $cdlQuery]);

                if (!empty($cdlResult['records'])) {
                    foreach ($cdlResult['records'] as $cdl) {
                        if (str_starts_with($cdl['LinkedEntityId'], '003')) {
                            $links[$cdl['ContentDocumentId']] = $cdl['LinkedEntityId'];
                        }
                    }
                }
            }
        }

        $mappedRecords = [];
        foreach ($result['records'] as $record) {
            $parentId = $links[$record['ContentDocumentId']] ?? null;
            if ($parentId) {
                // Ensure extension is included in the name if missing for ContentVersion (sometimes Title lacks it)
                // Actually Title on ContentVersion might not have the extension. We'd rather keep it exact, or we can use the original logic. 
                // In Salesforce, Title often excludes the file extension, but we'll mimic whatever was there to avoid logic changes.
                // If ContentSize is 0 and it's a LINK, use ContentUrl instead of VersionData
                $bodyUrl = $record['VersionData'];
                if (empty($record['ContentSize']) && !empty($record['ContentUrl'])) {
                    $bodyUrl = $record['ContentUrl'];
                }

                $mappedRecords[] = [
                    'Id' => $record['Id'],
                    'Body' => $bodyUrl,
                    'BodyLength' => $record['ContentSize'],
                    'Description' => $record['Description'],
                    'LastModifiedDate' => $record['LastModifiedDate'],
                    'CreatedDate' => $record['CreatedDate'],
                    'Name' => $record['Title'],
                    'ParentId' => $parentId,
                ];
            }
        }

        $result['records'] = $mappedRecords;
        return $result;
    }

    public function fetchAttachmentsById(array $ids)
    {
        $sf = new \App\Integrations\Salesforce\SalesforceService(
            $salesForce->sf_instance_url,
            $salesForce->sf_access_token
        );
        $query = "SELECT Body,BodyLength,Description,Id,LastModifiedDate,CreatedDate,Name,ParentId FROM Attachment WHERE ParentId IN ('".implode("','",array_keys($ids))."') Order by CreatedDate ASC";
        $result = $sf->apiCall('/services/data/v58.0/query', ['q' => $query]);
        return $result;
    }

    public function getAttachmentIds(array $attachInfo): array
    {
        $sfParentIds = collect($attachInfo)->pluck('ParentId')->unique()->toArray();

        $tickets = Ticket::select('id', 'sf_id')
            ->whereIn('sf_id', $sfParentIds)
            ->get();


        $idMap = $tickets->pluck('id', 'sf_id')->toArray();

        $filteredAttachments = collect($attachInfo)->filter(function ($attachment) use ($idMap) {
            return isset($idMap[$attachment['ParentId']]);
        })->values()->all();

        return [$filteredAttachments, $idMap];
    }

    public function getFile($fileUrl, $tries = 0, $sinkPath = null)
    {
        try {
            $request = Http::withOptions(['decode_content' => false])->timeout(120);

            // Do not send token to external domains like Amazon S3, and don't prepend instance URL
            if (str_starts_with($fileUrl, 'http://') || str_starts_with($fileUrl, 'https://')) {
                $fullUrl = $fileUrl;
            } else {
                $fullUrl = $this->instanceUrl . '/' . ltrim($fileUrl, '/');
                $request->withToken($this->accessToken);
            }

            if ($sinkPath) {
                $dir = dirname($sinkPath);
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $request->sink($sinkPath);
            }

            $response = $request->get($fullUrl);

            if ($response->successful()) {
                $this->line('<info>[Salesforce] Fetch Attachment.</info>');
                return $response;
            }
        } catch (\Exception $e) {
            $this->line('<error>[Salesforce] Exception: ' . $e->getMessage() . '</error>');

            $this->line('<info>[Salesforce] Getting New Token ( ' . $tries . ' )</info>');

            if ($tries < config('services.salesforce.timeout_max_tries')) {
                $this->client->connect();
                $salesForce = SalesForce::first();
                $this->accessToken = $salesForce->sf_access_token;
                return $this->getFile($fileUrl, $tries + 1, $sinkPath);
            }
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

}
