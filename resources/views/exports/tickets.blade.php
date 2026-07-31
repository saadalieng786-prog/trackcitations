<table>
    <tbody>
    <tr>
        <td><strong>Generated At</strong></td>
        <td>{{ $summary['generated_at'] }}</td>
        <td><strong>Total Tickets</strong></td>
        <td>{{ $summary['ticket_count'] }}</td>
        <td><strong>Tickets With Point Data</strong></td>
        <td>{{ $summary['tickets_with_points'] }}</td>
    </tr>
    <tr>
        <td><strong>Original Points Total</strong></td>
        <td>{{ number_format($summary['original_points_total'], 2) }}</td>
        <td><strong>Final Points Total</strong></td>
        <td>{{ number_format($summary['final_points_total'], 2) }}</td>
        <td><strong>Points Saved Total</strong></td>
        <td>{{ number_format($summary['points_saved_total'], 2) }}</td>
    </tr>
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>Company</th>
        <th>Ticket Count</th>
        <th>Points Saved Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($summary['by_company'] as $companySummary)
        <tr>
            <td>{{ $companySummary['company'] }}</td>
            <td>{{ $companySummary['ticket_count'] }}</td>
            <td>{{ number_format($companySummary['points_saved_total'], 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>Ticket Id</th>
        <th>Driver Name</th>
        <th>Driver Email</th>
        <th>Company</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zipcode</th>
        <th>Date Received</th>
        <th>Indicator</th>
        <th>Class Commercial?</th>
        <th>Roadside Inspection?</th>
        <th>Vehicle License Number</th>
        <th>Violation</th>
        <th>Citation Number</th>
        <th>DATAQ/DVER</th>
        <th>Ticket Type</th>
        <th>Beginning Fine Amount</th>
        <th>Final Fine Amount</th>
        <th>Total DVER Points</th>
        <th>Total DVER Points Removed</th>
        <th>Original Points Value</th>
        <th>Final Points Value</th>
        <th>Points Saved</th>
        <th>Court Name</th>
        <th>Court Date</th>
        <th>Court Address</th>
        <th>Attorney Name</th>
        <th>Attorney Address</th>
        <th>Attorney Response</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tickets as $ticket)
        <tr>
            <td>{{ $ticket->id }}</td>
            <td>{{ $ticket->name }}</td>
            <td>{{ $ticket->user_email }}</td>
            <td>{{ $ticket->company?->name }}</td>
            <td>{{ $ticket->address }}</td>
            <td>{{ $ticket->city }}</td>
            <td>{{ $ticket->state }}</td>
            <td>{{ $ticket->zip }}</td>
            <td>{{ \Carbon\Carbon::parse($ticket->date_issued)->toDateString() }}</td>
            <td>{{ $ticket->indicator }}</td>
            <td>{{ $ticket->class_commercial }}</td>
            <td>{{ $ticket->road_side_inspection }}</td>
            <td>{{ $ticket->vehicle_lic_no }}</td>
            <td>{{ $ticket->violation?->violation }}</td>
            <td>{{ $ticket->citation_no }}</td>
            <td>{{ $ticket->isDverDataq()['DVER'] ? 'DVER' : '' }} {{ $ticket->isDverDataq()['DATAQ'] ? 'DATAQ' : '' }} </td>
            <td>{{ $ticket->ticket_type }}</td>
            <td>{{ $ticket->beginning_fine_amount }}</td>
            <td>{{ $ticket->final_fine_amount }}</td>
            <td>{{ $ticket->total_dver_points__c }}</td>
            <td>{{ $ticket->total_dver_points_removed__c }}</td>
            <td>{{ $ticket->original_points_value !== null ? number_format($ticket->original_points_value, 2) : '' }}</td>
            <td>{{ $ticket->final_points_value !== null ? number_format($ticket->final_points_value, 2) : '' }}</td>
            <td>{{ number_format($ticket->points_saved, 2) }}</td>
            <td>{{ $ticket->court_name }}</td>
            <td>{{ $ticket->court_date ? \Carbon\Carbon::parse($ticket->court_date)->toDateString() : '' }}</td>
            <td>{{ $ticket->court_address }}</td>
            <td>{{ $ticket->attorney?->user->name }}</td>
            <td>{{ $ticket->attorney?->user->address }}</td>
            <td>{{ $ticket->attorney_response }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
