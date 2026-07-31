<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Exports;

use App\Filters\TicketFilters;
use App\Models\Ticket;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    public function query()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $user = request()->user() ?? auth()->user();
        $ticketFilters = new TicketFilters(request());

        $ticketsQuery = Ticket::query()
            ->with([
                'company:id,name',
                'attorney.user:id,name,address',
                'violation:id,violation',
                'attachments:id,ticket_id,filename'
            ])
            ->active()
            ->filterByRole($user);

        return $ticketFilters->apply($ticketsQuery)->orderByDesc('id');
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function headings(): array
    {
        return [
            'Ticket Id',
            'Driver Name',
            'Driver Email',
            'Company',
            'Address',
            'City',
            'State',
            'Zipcode',
            'Date Received',
            'Indicator',
            'Class Commercial?',
            'Roadside Inspection?',
            'Vehicle License Number',
            'Violation',
            'Citation Number',
            'DATAQ/DVER',
            'Ticket Type',
            'Beginning Fine Amount',
            'Final Fine Amount',
            'Total DVER Points',
            'Total DVER Points Removed',
            'Original Points Value',
            'Final Points Value',
            'Points Saved',
            'Court Name',
            'Court Date',
            'Court Address',
            'Attorney Name',
            'Attorney Address',
            'Attorney Response',
        ];
    }

    /**
     * @param Ticket $ticket
     */
    public function map($ticket): array
    {
        $isDverDataq = $ticket->isDverDataq();

        return [
            $ticket->id,
            $ticket->name,
            $ticket->user_email,
            $ticket->company?->name,
            $ticket->address,
            $ticket->city,
            $ticket->state,
            $ticket->zip,
            $ticket->date_issued ? Carbon::parse($ticket->date_issued)->toDateString() : '',
            $ticket->indicator,
            $ticket->class_commercial,
            $ticket->road_side_inspection,
            $ticket->vehicle_lic_no,
            $ticket->violation?->violation,
            $ticket->citation_no,
            trim(($isDverDataq['DVER'] ? 'DVER ' : '') . ($isDverDataq['DATAQ'] ? 'DATAQ' : '')),
            $ticket->ticket_type,
            $ticket->beginning_fine_amount,
            $ticket->final_fine_amount,
            $ticket->total_dver_points__c,
            $ticket->total_dver_points_removed__c,
            $ticket->original_points_value !== null ? number_format($ticket->original_points_value, 2) : '',
            $ticket->final_points_value !== null ? number_format($ticket->final_points_value, 2) : '',
            number_format($ticket->points_saved, 2),
            $ticket->court_name,
            $ticket->court_date ? Carbon::parse($ticket->court_date)->toDateString() : '',
            $ticket->court_address,
            $ticket->attorney?->user?->name,
            $ticket->attorney?->user?->address,
            $ticket->attorney_response,
        ];
    }
}
