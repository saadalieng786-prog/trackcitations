<?php

namespace App\Integrations\Salesforce;

use App\Models\Attorney;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Manager;
use App\Models\SalesForce;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Support\AttachmentStorage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

class SalesforceSyncService
{
    protected array $companyIds = [];
    protected array $ticketIds = [];
    protected array $attorneyInfo = [];
    protected array $driverInfo = [];
    protected array $driverEmails = [];
    protected array $agentInfo = [];
    protected array $attachedIds = [];
    protected array $companyParents = [];


    public function __construct(private ConsoleOutput $output, private SalesforceClient $client = new SalesforceClient())
    {
    }

    public function sync(array $records): void
    {
        $this->output->writeln('<info>[SalesforceSync] Starting sync of ' . count($records) . ' records. </info>');

        $this->prepareIds($records);
        $this->loadExistingFromDb();

        foreach ($records as $index => $record) {
            $this->output->writeln("<info>[SalesforceSync] Processing record " . ($index + 1) . "/" . count($records) . "</info>");
            $this->syncSingleRecord($record);
        }

        $this->syncCompanyHierarchy();

        $this->output->writeln('<info>[SalesforceSync] Sync completed.</info>');
    }

    protected function prepareIds(array $records): void
    {
        foreach ($records as $record) {
            if (!isset($this->companyIds[$record['Account']['Id']])) {
                $this->companyIds[$record['Account']['Id']] = null;
            }
            if (!isset($this->ticketIds[$record['Id']])) {
                $this->ticketIds[$record['Id']] = null;
            }
            if (!isset($this->driverInfo[$record['Id']])) {
                $this->driverInfo[$record['Id']] = null;
            }
            if (!isset($this->driverEmails[$record['Id']])) {
                $this->driverEmails[$record['Id']] = $record['Email'] ?? null;
            }
            if (!isset($this->attorneyInfo[$record['Attorney_Email_Address__c'] ?? ''])) {
                $this->attorneyInfo[$record['Attorney_Email_Address__c']] = null;
            }
            if (!isset($this->agentInfo[$record['Account']['Sales_Agent_Email__c'] ?? ''])) {
                $this->agentInfo[$record['Account']['Sales_Agent_Email__c']] = null;
            }
            $accountId = $record['Account']['Id'] ?? null;
            $parentAccountId = $record['Account']['ParentId'] ?? null;
            if ($accountId) {
                $this->companyParents[$accountId] = $parentAccountId;
            }
            if ($parentAccountId && !isset($this->companyIds[$parentAccountId])) {
                $this->companyIds[$parentAccountId] = null;
            }
        }
    }

    protected function loadExistingFromDb(): void
    {
        // Companies
        $companies = DB::table('companies')
            ->whereIn('sf_id', array_keys($this->companyIds))
            ->get(['id', 'sf_id']);

        foreach ($companies as $company) {
            $this->companyIds[$company->sf_id] = $company->id;
        }

        // Attorneys
        $attorneys = User::role('attorney')
            ->whereIn('email', array_keys($this->attorneyInfo))
            ->get(['id', 'email']);

        foreach ($attorneys as $lawyer) {
            $this->attorneyInfo[$lawyer->email] = $lawyer->id;
        }

        // Tickets
        $tickets = Ticket::whereIn('sf_id', array_keys($this->ticketIds))
            ->get(['id', 'sf_id']);

        foreach ($tickets as $ticket) {
            $this->ticketIds[$ticket->sf_id] = $ticket->id;
        }

        $drivers = Driver::query()
            ->whereIn('sf_id', array_keys($this->driverInfo))
            ->get(['id', 'sf_id']);

        foreach ($drivers as $driver) {
            $this->driverInfo[$driver->sf_id] = $driver->id;
        }

        $driverUsers = User::role(User::ROLE_DRIVER)
            ->whereIn('email', array_filter($this->driverEmails))
            ->get(['email', 'roleable_id']);

        foreach ($driverUsers as $driverUser) {
            foreach ($this->driverEmails as $sfId => $email) {
                if ($email === $driverUser->email && $this->driverInfo[$sfId] === null) {
                    $this->driverInfo[$sfId] = $driverUser->roleable_id;
                }
            }
        }
    }

    protected function syncSingleRecord(array $record): void
    {
        DB::beginTransaction();

        try {
            $companyId = $this->syncCompany($record['Account'] ?? []);
            $this->syncDriver($record, $companyId);
            $lawyerId = $this->syncAttorney($record);
            $this->syncTicket($record, $companyId, $lawyerId);

            SalesForce::first()->update([
                'sf_last_sync_time' => $record['SystemModstamp'] ?? $record['LastModifiedDate']
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $message = '[SalesforceSync] Error syncing record: ' . $e->getMessage();
            SalesForce::first()->update(['status' => SalesForce::STATUS_FAILED, 'reason' => $message]);
            SalesforceSyncLogger::error('Record sync failed', [
                'message' => $e->getMessage(),
                'contact_id' => $record['Id'] ?? null,
                'account_id' => $record['Account']['Id'] ?? null,
            ]);

            $this->output->writeln('<error>'.$message.'</error>');
        }
    }

    protected function syncCompany(array $account): int
    {
        $accountId = $account['Id'] ?? null;
        if (! $accountId) {
            throw new Exception('Salesforce Account Id is missing on contact record.');
        }

        // Prefer Account email fields for the company manager.
        // Contact.Email is reserved for the driver user to avoid unique email collisions.
        $managerEmail = $this->firstFilled([
            $account['Contact_Email__c'] ?? null,
            $account['Primary_Contact_Email__c'] ?? null,
            $account['Citation_Tracker_User_Email__c'] ?? null,
            $account['Alternate_Email__c'] ?? null,
        ]);

        $managerData = [
            'phone' => $account['Phone'] ?? ($account['Text_Phone__c'] ?? null),
            'address' => $account['BillingStreet'] ?? null,
            'city' => $account['BillingCity'] ?? null,
            'state' => $account['BillingState'] ?? null,
            'zip' => $account['BillingPostalCode'] ?? null,
            'name' => $account['Name'] ?? ($managerEmail ?: 'Salesforce Manager'),
            'password' => Hash::make($managerEmail ?: Str::random(32)),
            'email' => $managerEmail,
        ];

        $companyData = [
            'name' => $account['Name'] ?? ('Salesforce Account '.$accountId),
            'parent_company_id' => $this->resolveParentCompanyId($account['ParentId'] ?? null),
            'ct_email' => $account['Citation_Tracker_User_Email__c'] ?? null,
            'ct_fname' => $account['Citation_Tracker_User_First_Name__c'] ?? null,
            'ct_lname' => $account['Citation_Tracker_User_Last_Name__c'] ?? null,
            'dot' => $account['DOT_Number__c'] ?? null,
            'sf_id' => $accountId,
        ];

        if (($this->companyIds[$accountId] ?? null) === null) {
            $company = Company::create($companyData);

            if (filled($managerEmail)) {
                $managerUser = User::where('email', $managerEmail);
                if ($managerUser->exists()) {
                    $user = $managerUser->first();
                    $manager = $user->roleable;
                } else {
                    $manager = Manager::create([]);
                    $user = $manager->user()->create($managerData);
                    $user->assignRole(User::ROLE_COMPANY_ADMIN);
                }

                if ($manager) {
                    $manager->companies()->syncWithoutDetaching([
                        $company->id => ['is_write_access' => true],
                    ]);
                }
            } else {
                SalesforceSyncLogger::info('Company synced without manager user (no Account email fields)', [
                    'account_id' => $accountId,
                    'company_id' => $company->id,
                ]);
            }

            $this->companyIds[$accountId] = $company->id;
        } else {
            DB::table('companies')
                ->where('id', $this->companyIds[$accountId])
                ->update($companyData);
        }

        return $this->companyIds[$accountId];
    }

    protected function syncDriver(array $record, int $companyId): ?int
    {
        $sfId = $record['Id'] ?? null;
        $email = $record['Email'] ?? null;

        if (! $sfId || ! $email) {
            return null;
        }

        $name = trim(implode(' ', array_filter([
            $record['FirstName'] ?? null,
            $record['LastName'] ?? null,
        ])));

        $userData = [
            'name' => $name !== '' ? $name : ($record['Name'] ?? $email),
            'email' => $email,
            'dob' => $record['Date_of_Birth__c'] ?? null,
            'address' => $record['MailingStreet'] ?: ($record['Driver_Address__c'] ?? null),
            'city' => $record['MailingCity'] ?: ($record['Driver_City__c'] ?? null),
            'state' => $record['MailingState'] ?: ($record['Driver_State__c'] ?? null),
            'zip' => $record['MailingPostalCode'] ?: ($record['Driver_Zip_Code__c'] ?? null),
            'phone' => $record['MobilePhone'] ?: ($record['Phone'] ?? null),
        ];

        $driverId = $this->driverInfo[$sfId] ?? null;
        $driver = $driverId ? Driver::find($driverId) : null;

        if (! $driver) {
            $driverUser = User::role(User::ROLE_DRIVER)->where('email', $email)->first();
            if ($driverUser && $driverUser->roleable instanceof Driver) {
                $driver = $driverUser->roleable;
            }
        }

        if (! $driver) {
            // Avoid unique email collisions if this email already belongs to another user role.
            $existingUser = User::where('email', $email)->first();
            if ($existingUser && ! ($existingUser->roleable instanceof Driver)) {
                SalesforceSyncLogger::info('Skipping driver user create; email already used by another role', [
                    'email' => $email,
                    'existing_roleable' => $existingUser->roleable_type,
                    'contact_id' => $sfId,
                ]);

                $driver = Driver::create([
                    'company_id' => $companyId,
                    'sf_id' => $sfId,
                ]);
                $this->driverInfo[$sfId] = $driver->id;

                return $driver->id;
            }

            $driver = Driver::create([
                'company_id' => $companyId,
                'sf_id' => $sfId,
            ]);

            $driverUser = $driver->user()->create($userData + [
                'password' => Hash::make($email),
            ]);
            $driverUser->assignRole(User::ROLE_DRIVER);
        } else {
            $driver->update([
                'company_id' => $companyId,
                'sf_id' => $sfId,
            ]);

            if ($driver->user) {
                $driver->user->update(array_filter($userData, fn ($value) => $value !== null && $value !== ''));
                if (! $driver->user->hasRole(User::ROLE_DRIVER)) {
                    $driver->user->assignRole(User::ROLE_DRIVER);
                }
            } else {
                $driverUser = $driver->user()->create($userData + [
                    'password' => Hash::make($email),
                ]);
                $driverUser->assignRole(User::ROLE_DRIVER);
            }
        }

        $this->driverInfo[$sfId] = $driver->id;

        return $driver->id;
    }

    protected function syncAttorney(array $record): ?int
    {
        $email = $record['Attorney_Email_Address__c'] ?? null;

        if (!$email || $this->attorneyInfo[$email]) {
            return $this->attorneyInfo[$email];
        }

        $lawyerData = [
            'name' => $record['Attorney__c'],
            'address' => $record['Attorney_Address__c'],
            'phone' => $record['Attorney_Number__c'],
            'email' => $email,
            'city' => $record['Attorney_City__c'],
            'state' => $record['Attorney_State__c'],
            'zip' => $record['Attorney_Zip__c'],
            'password' => Hash::make($email),
        ];

        if (!array_filter($lawyerData)) return null;

        $attorney = Attorney::create();

        $user = $attorney->user()->create($lawyerData);
        $user->assignRole('attorney');


        $lawyerId = $attorney->id;
        $this->attorneyInfo[$email] = $lawyerId;

        return $lawyerId;
    }

    protected function syncTicket(array $record, int $companyId, ?int $lawyerId): int
    {
        $sfId = $record['Id'];
        $ticketMapped = [
            'sf_id' => $record['Id'],
            'user_email' => $record['Email'],
            'citation_type' => $record['Citation_Type__c'],
            'address' => $record['Driver_Address__c'],
            'city' => $record['Driver_City__c'],
            'state' => $record['Driver_State__c'],
            'zip' => $record['Driver_Zip_Code__c'],
            'birthdate' => $record['Date_of_Birth__c'],
            'date_issued' => $this->parseSfDate($record['Date_Issued__c']),
            'court_date' => $this->parseSfDate($record['Court_Date__c']),
            'court_name' => $record['Court_Name__c'],
            'court_address' => $record['Court_Address__c'],
            'court_phone' => $record['Court_Phone_Number__c'] ?? '',
            'county' => $record['County__c'],
            'ticket_number' => $record['Ticket_Number__c'],
            'ticket_dispo' => $record['Dispo__c'],
            'road_side_inspection' => $record['Roadside_Inspection__c'],
            'sales_agent' => $record['Sales_Agent__c'],
            'sales_agent_name' => $record['Account']['Sales_Agent_Name__c'] ?? null,
            'sales_agent_email' => $record['Account']['Sales_Agent_Email__c'] ?? null,
            'name' => $record['Name'],
            'fname' => $record['FirstName'] ?? null,
            'lname' => $record['LastName'] ?? null,
            'company_id' => $companyId,
            'lawyer_email' => $record['Attorney_Email_Address__c'],
            'attorney_id' => $lawyerId ?: null,
            'indicator' => $this->getIndicator($record),
            'disposition__c' => $record['Disposition__c'],
            'confirmed__c' => $record['Confirmed__c'],
            'canceled__c' => $record['Canceled__c'],
            'dataq_number__c' => $record['DataQ_Number__c'],
            'roadside_inspection_number__c' => $record['Roadside_Inspection_Number__c'],
            'ticket_type' => $record['Ticket_Type__c'],
            'beginning_fine_amount' => $record['Beginning_Fine_Amount__c'],
            'final_fine_amount' => $record['Final_Fine_Amount__c'],
            'processor_name' => $record['Processor_Name__c'],
            'processor_email' => $record['Processor_Email__c'],
            'processor_ph_number' => $record['Processor_Ph_Number__c'],
            'processor_notes_to_attorney' => $record['Processor_Notes_To_Attorney__c'],
            'total_dver_points__c' => $record['Total_DVER_Points__c'],
            'total_dver_points_removed__c' => $record['Total_DVER_Points_REMOVED__c'],
            'attorney_response' => $record['Attorney_response__c'],
            'road_side_inspection_results' => $record['Dispo_Results_From_Attorney__c'],
            'is_approved' => 1,
            'created_at' => now(),
            'updated_at' => now(),
            'updated_by' => 'admin',
            'status' => 1,
            'violation_id' => 208,
        ];

        if ($this->ticketIds[$sfId] === null) {
            $ticket = Ticket::create($ticketMapped);
            $this->ticketIds[$sfId] = $ticket->id;
        } else {
            Ticket::where('id', $this->ticketIds[$sfId])
                ->update($ticketMapped);
        }

        return $this->ticketIds[$sfId];
    }

    protected function syncCompanyHierarchy(): void
    {
        if (empty($this->companyParents)) {
            return;
        }

        $companyMap = Company::query()
            ->whereIn('sf_id', array_keys($this->companyIds))
            ->get(['id', 'sf_id'])
            ->pluck('id', 'sf_id');

        foreach ($this->companyParents as $sfId => $parentSfId) {
            $companyId = $companyMap[$sfId] ?? null;
            if (! $companyId) {
                continue;
            }

            $parentId = $parentSfId ? ($companyMap[$parentSfId] ?? null) : null;
            if ($parentId === $companyId) {
                $parentId = null;
            }

            Company::where('id', $companyId)->update([
                'parent_company_id' => $parentId,
            ]);
        }
    }

    protected function resolveParentCompanyId(?string $parentSfId): ?int
    {
        if (! $parentSfId) {
            return null;
        }

        if (($this->companyIds[$parentSfId] ?? null) !== null) {
            return $this->companyIds[$parentSfId];
        }

        return Company::query()
            ->where('sf_id', $parentSfId)
            ->value('id');
    }


    public function syncAttachments(array $attachments, array $ticketIds, SalesforceService $sf): void
    {
        $this->output->writeln("<info>Starting attachments sync...</info>");
        $disk = AttachmentStorage::ticketDisk();

        $sfIds = array_column($attachments, 'Id', 'Id');

        // Load existing attachment modified dates
        $existing = TicketAttachment::whereIn('sf_id', array_keys($sfIds))
            ->get(['sf_id', 'sf_last_modified_date'])
            ->keyBy('sf_id');

        foreach ($existing as $sfId => $record) {
            $this->attachedIds[$sfId] = $record->sf_last_modified_date;
        }

        $skippedXls = $unModified = $downloaded = 0;

        foreach ($attachments as $file) {
            $parentId = $file['ParentId'] ?? null;
            $ticketId = $ticketIds[$parentId] ?? null;

            if (!$ticketId) {
                continue;
            }

            $this->output->writeln("<info>Processing attachment: {$file['Name']}...</info>");

            // Skip Excel
            if (str_ends_with($file['Name'], '.xls') || str_ends_with($file['Name'], '.xlsx')) {
                $skippedXls++;
                continue;
            }

            $sfId = $file['Id'];
            $lastModified = $file['LastModifiedDate'];


            if (isset($this->attachedIds[$sfId]) && $this->attachedIds[$sfId] === $lastModified) {
                $unModified++;
                continue;
            }

            if (str_starts_with($sfId, '068')) {
                SalesForce::first()->update([
                    'sf_file_last_sync_time' => $lastModified
                ]);
            } else {
                SalesForce::first()->update([
                    'sf_att_last_sync_time' => $lastModified
                ]);
            }

            try {
                $fileName = str_replace(['#','/','?','"',"'",' ','--'], '-', $file['Name']);
                $fileName = rtrim($fileName, '.');
                $createdDate = Carbon::parse($file['CreatedDate'])->format('Y/m/d');
                $relativePath = "attachments/{$createdDate}/{$parentId}/{$sfId}-{$fileName}";


                if (Storage::disk($disk)->exists($relativePath)) {
                    $this->output->writeln("<comment>Skipping : {$file['Name']} Already Exists</comment>");
                    $unModified++;
                    continue;
                }

                $sinkPath = AttachmentStorage::isLocalDisk($disk)
                    ? Storage::disk($disk)->path($relativePath)
                    : null;
                $response = $sf->getFile($file['Body'], 0, $sinkPath);

                if (is_array($response)) {
                    Log::critical("Stored on error" . json_encode($response));
                    $this->client->connect();
                    $response = $sf->getFile($file['Body'], 0, $sinkPath);
                    if (is_array($response)) {continue;}
                }
                if ($response->ok()) {
                    if (! AttachmentStorage::isLocalDisk($disk)) {
                        AttachmentStorage::storeSalesforceContents($relativePath, $response->body());
                    }

                    $ticket = Ticket::findOrFail($ticketId);

                    $attachmentData = [
                        'filename' => $fileName,
                        'path' => Storage::disk($disk)->url($relativePath),
                        'sf_id' => $sfId,
                        'description' => $file['Description'] ?? '',
                        'sf_last_modified_date' => Carbon::parse($lastModified),
                        'last_modified_date' => Carbon::parse($lastModified),
                    ];
                    $ticket->attachments()->updateOrCreate(
                        ['sf_id' => $sfId],
                        $attachmentData
                    );

                    $downloaded++;
                    $this->output->writeln("<info>Saved attachment: $relativePath</info>");
                } else {
                    $this->output->writeln("<error>Failed to download file content for {$file['Body']}</error>");
                }
            } catch (\Exception $e) {
                $message = "<error>Error: {$e->getMessage()}";
                SalesForce::first()->update(['status' => SalesForce::STATUS_FAILED, 'reason' => $message]);

                $this->output->writeln($message);
            }
        }

        $this->output->writeln("<info>Skipped XLS: $skippedXls</info>");
        $this->output->writeln("<info>Unmodified: $unModified</info>");
        $this->output->writeln("<info>Downloaded: $downloaded</info>");
        $this->output->writeln("<info>Attachment sync complete.</info>");
    }

    protected function parseSfDate(?string $date): ?string
    {
        if (!$date) return null;
        return date('Y-m-d H:i:s', strtotime(str_replace(['T', '.000+0000'], [' ', ''], $date)));
    }

    protected function firstFilled(array $values): ?string
    {
        foreach ($values as $value) {
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return null;
    }

    protected function getIndicator(array $record): string
    {
        if (!empty($record['Disposition__c'])) return 'Disposed';
        if (!empty($record['Canceled__c'])) return 'Canceled';
        if (!empty($record['Confirmed__c'])) return 'Sent to Attorney';
        return 'Received';
    }

    public function buildTicketMap(): array
    {
        return Ticket::whereNotNull('sf_id')
            ->pluck('id', 'sf_id')
            ->toArray();
    }
}
