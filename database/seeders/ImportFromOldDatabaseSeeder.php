<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace Database\Seeders;

use App\Models\Attorney;
use App\Models\Company;
use App\Models\Manager;
use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\User;
use App\Models\Violation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportFromOldDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attorneyMappings = [];
        // Connect to the old database
        $oldConnection = DB::connection('old_db');
        // Import Lawyers.
        $oldAttorneys = $oldConnection->table('ctn_laywer')->get();
        foreach ($oldAttorneys as $oldAttorney) {
            $emailExists = \App\Models\User::where('email', $oldAttorney->Email)->exists();
            if (!(!$oldAttorney->Email || $emailExists || !$oldAttorney->Name)) {
                $attorney = Attorney::create([]);
                $attorneyUser = User::create([
                    'name' => $oldAttorney->Name,
                    'email' => $oldAttorney->Email,
                    'phone' => $oldAttorney->Phone,
                    'address' => $oldAttorney->Address,
                    'password' => $oldAttorney->password,
                    'roleable_id' => $attorney->id,
                    'roleable_type' => Attorney::class,
                ]);
                $attorneyUser->assignRole('attorney');

                $attorneyMappings[$oldAttorney->id] = $attorney->id;
            }
        }
        // Import Companies.
        $companies = $oldConnection->table('cdl_company')->get();
        foreach ($companies as $company) {
            // Import Old Companies To The New System.
            $newCompany = Company::create([
                'name' => $company->company_name,
                'ct_email' => $company->Citation_Tracker_User_Email,
                'ct_fname' => $company->Citation_Tracker_User_First_Name,
                'ct_lname' => $company->Citation_Tracker_User_Last_Name,
                'dot' => $company->DOT_Number,
                'sf_id' => $company->sf_id
            ]);

            // Import Managers To Corresponding Company.
            $companiesUsers = $oldConnection->table('cdl_reg_tb')->where([['company_id', $company->id]])->get();
            foreach ($companiesUsers as $companiesUser) {
                if ($companiesUser->access_level == 2 || $companiesUser->access_level == 3) {
                    $emailExists = \App\Models\User::where('email', $companiesUser->email)->first();
                    if (!$emailExists) {
                        $manager = Manager::create([]);

                        $manager->companies()->attach($newCompany->id, [
                            'is_write_access' => $companiesUser->access_level == 2 || $company->company_email == $companiesUser->email
                        ]);
                        $managerUser = User::create([
                            'name' => $companiesUser->fname.' '.$companiesUser->lname,
                            'email' => $companiesUser->email,
                            'phone' => $companiesUser->Phone,
                            'address' => $companiesUser->address,
                            'city' => $companiesUser->city,
                            'state' => $companiesUser->state,
                            'zip' => $companiesUser->zip,
                            'timezone' => $companiesUser->TimeZone ?? 'UTC',
                            'password' => $companiesUser->pwd,
                            'roleable_id' => $manager->id,
                            'roleable_type' => Manager::class,
                        ]);
                        $managerUser->assignRole('manager');
                    } else {
                        $manager = Manager::find($emailExists->roleable_id);
                        if ($manager) {
                            $manager->companies()->attach($newCompany->id, [
                                'is_write_access' => $companiesUser->access_level == 2 || $company->company_email == $companiesUser->email
                            ]);
                        } else {
                            dump($manager);
                        }
                    }
                }
            }


            // Import Company Contacts.
            $emailExists = \App\Models\User::where('email', $company->company_email)->first();
            if (!$emailExists && !empty($company->company_email) && !empty($company->primary_contact_name)) {
                $newCompany->contacts()->create([
                    'name' => $company->primary_contact_name,
                    'email' => $company->company_email,
                    'phone' => $company->phone,
                    'cell' => $company->cell,
                ]);
            }
            $emailExists = \App\Models\User::where('email', $company->secondary_email)->first();
            if (!$emailExists && !empty($company->secondary_email) && !empty($company->secondary_name)) {
                $newCompany->contacts()->create([
                    'name' => $company->secondary_name,
                    'email' => $company->secondary_email,
                    'phone' => $company->secondary_phone,
                    'cell' => $company->secondary_cell,
                ]);
            }

            // Import Company Tickets.
            $companyTickets = $oldConnection->table('ctn_ticket')->where([['company_id', $company->id]])->get();
            foreach ($companyTickets as $companyTicket) {
                if (isset($attorneyMappings[$companyTicket->lawyer_id])) {
                    $attorney_id = $attorneyMappings[$companyTicket->lawyer_id];
                } else {
                    $attorney_id = null;
                }
                $ticket = Ticket::create([
                    'name' => $companyTicket->Name,
                    'company_id' => $newCompany->id,
                    'address' => $companyTicket->Address,
                    'birthdate' => $companyTicket->Birthdate,
                    'city' => $companyTicket->City,
                    'state' => $companyTicket->State,
                    'zip' => $companyTicket->Zip,
                    'dl_number' => $companyTicket->Dl_Number,
                    'class_commercial' => $companyTicket->Class_Commercial === 'Yes' ? 'Yes' : 'No',
                    'vehicle_lic_no' => $companyTicket->Vehicle_Lic_No,
                    'citation_type' => $companyTicket->Citation_Type,
                    'violation_id' => Violation::firstOrCreate(['violation' => $companyTicket->violation])->id,
                    'location_violation' => $companyTicket->Location_violation,
                    'city_county_occurrence' => $companyTicket->City_County_Occurence,
                    'speed_approx' => $companyTicket->Speed_Approx,
                    'arresting_officer_name' => $companyTicket->Arresting_Officer_Name,
                    'note' => $companyTicket->Note,
                    'file' => $companyTicket->file,
                    'path' => $companyTicket->path,
                    'date_time' => $companyTicket->date_time,
                    'user_email' => $companyTicket->user_email,
                    'indicator' => $companyTicket->indicator,
                    'disposition__c' => $companyTicket->Disposition__c,
                    'confirmed__c' => $companyTicket->Confirmed__c,
                    'canceled__c' => $companyTicket->Canceled__c,
                    'lawyer_email' => $companyTicket->lawyer_email,
                    'admin_note' => $companyTicket->Admin_note,
                    'citation_no' => $companyTicket->citation_no,
                    'court_date' => $companyTicket->court_date === '0000-00-00 00:00:00' ? null : $companyTicket->court_date,
                    'court_address' => $companyTicket->court_address,
                    'court_phone' => $companyTicket->court_phone,
                    'ticket_dispo' => $companyTicket->ticket_dispo,
                    'date_issued' => $companyTicket->date_issued === '0000-00-00' ? null : $companyTicket->date_issued,
                    'court_name' => $companyTicket->court_name,
                    'county' => $companyTicket->county,
                    'ticket_number' => $companyTicket->ticket_number,
                    'attorney_id' => $attorney_id,
                    'road_side_inspection' => $companyTicket->road_side_inspection,
                    'road_side_inspection_results' => $companyTicket->road_side_inspection_results,
                    'sales_agent' => $companyTicket->sales_agent,
                    'fname' => $companyTicket->fname,
                    'lname' => $companyTicket->lname,
                    'sales_agent_name' => $companyTicket->sales_agent_name,
                    'sales_agent_email' => $companyTicket->sales_agent_email,
                    'sales_agent_id' => $companyTicket->sales_agent_id,
                    'sf_id' => $companyTicket->sf_id,
                    'dataq_number__c' => $companyTicket->DataQ_Number__c,
                    'roadside_inspection_number__c' => $companyTicket->Roadside_Inspection_Number__c,
                    'ticket_type' => $companyTicket->Ticket_Type,
                    'beginning_fine_amount' => $companyTicket->Beginning_Fine_Amount,
                    'final_fine_amount' => $companyTicket->Final_Fine_Amount,
                    'processor_name' => $companyTicket->Processor_Name,
                    'processor_email' => $companyTicket->Processor_Email,
                    'processor_ph_number' => $companyTicket->Processor_Ph_Number,
                    'processor_notes_to_attorney' => $companyTicket->Processor_Notes_To_Attorney,
                    'total_dver_points__c' => $companyTicket->Total_DVER_Points__c,
                    'total_dver_points_removed__c' => $companyTicket->Total_DVER_Points_REMOVED__c,
                    'attorney_response' => $companyTicket->Attorney_response,
                    'updated_at' => $companyTicket->updated_at,
                    'created_at' => $companyTicket->updated_at,
                ]);
                $ticketAttachments = $oldConnection->table('ctn_ticket_attachment')->where([['ticket_id', $companyTicket->id]])->get();
                foreach ($ticketAttachments as $ticketAttachment) {
                    $attachment = $ticket->attachments()->create([
                        'sf_id' => $ticketAttachment->sf_id,
                        'filename' => $ticketAttachment->Name,
                        'description' => $ticketAttachment->Description,
                        'path' => $ticketAttachment->Path,
                        'sf_last_modified_date' => Carbon::parse($ticketAttachment->SF_LAST_MODIFIED_DATE),
                        'last_modified_date' => $ticketAttachment->LastModifiedDate,
                        'notified' => $ticketAttachment->notified,
                        'exist' => $ticketAttachment->exist,
                        'checked' => $ticketAttachment->checked
                    ]);
                    if (!$attachment) {
                        dd($ticket, $companyTicket);
                    }
                }
            }
        }
    }
}
