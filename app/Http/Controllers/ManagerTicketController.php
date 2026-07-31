<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Filters\TicketFilters;
use App\Models\Ticket;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ManagerTicketController extends TicketController
{
    use AuthorizesRequests;  // Ensure this is included

    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilters $filters)
    {
        //
        if (request()->ajax()) {
            $data = Ticket::filter($filters)->filterByRole(request()->user())->with('company');


            if (\request('search')['value']) {
                $search = request('search')['value'];

                // Add search conditions
                $data = $data->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('created_at', 'like', "%{$search}%")
                        ->orWhereHas('company', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            }

            // Handle ordering
            if (request()->has('order')) {
                $columns = ['id', 'name', 'date_issued', 'state', 'indicator']; // Adjust to match your table columns
                $order = request('order', 'id');
                $columnIndex = $order[0]['column'];
                $direction = $order[0]['dir'];

                // Ensure column exists in your defined columns array
                if (isset($columns[$columnIndex])) {
                    $data = $data->orderBy($columns[$columnIndex], $direction);
                }
            }

            // Pagination
            $length = request('length', 10);
            $start = request('start', 0);
            $page = ($start / $length) + 1;

            $data = $data->paginate($length, ['*'], 'page', $page);

            // Add action column
            $portal = auth()->user()->portalRoutePrefix();

            $data->getCollection()->transform(function ($item) use ($portal) {

                $item->indicator = $item->indicator === Ticket::INDICATOR_PENDING ? '<i class="badge bg-danger text-white">'.Ticket::INDICATOR_PENDING.'</i>' : $item->indicator;
                $item->original_points_value = $item->original_points_value !== null ? number_format($item->original_points_value, 2, '.', '') : '';
                $item->final_points_value = $item->final_points_value !== null ? number_format($item->final_points_value, 2, '.', '') : '';
                $item->points_saved = number_format($item->points_saved, 2, '.', '');


                $item->action = '<a href="'. route($portal.'.tickets.show', $item->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-eye text-xl leading-none"></i>
                                    </a>';

                $item->action .= !auth()->user()->roleable->canWriteToCompany($item->company_id) ? '' : '
                                <a href="'.route($portal.'.tickets.edit', $item->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                    <i class="ti ti-edit text-xl leading-none"></i>
                                </a>';
                return $item;
            });

            // Response
            return response()->json([
                'draw' => request()->get('draw'),
                'recordsTotal' => $data->total(),
                'recordsFiltered' => $data->total(),
                'data' => $data->items(),
            ]);
        }




        return view('manager.tickets.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('create', new Ticket());

        return view('manager.tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_email' => 'required|email',
            'name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'address' => 'required',
            'indicator' => 'in:' . implode(',', Ticket::INDICATORS_ALLOWED),
            'class_commercial' => 'in:Yes,No',
            'road_side_inspection' => 'in:Yes,No',
            'date_issued' => 'required|date',
            'vehicle_lic_no' => 'required',
            'violation_id' => 'required|exists:violations,id',
            'citation_no' => '',
            'ticket_type' => '',
            'court_name' => '',
            'court_date' => '',
            'court_address' => '',
            'attorney_id' => 'exists:attorneys,id',
            'attorney_response' => 'in:Accepted,Rejected',
            'processor_name' => '',
            'processor_email' => '',
            'processor_ph_number' => '',
            'processor_notes_to_attorney' => '',
        ]);
        // Create a new Ticket record with the validated data
        $ticket = Ticket::create($request->only([
            'user_email',
            'name',
            'company_id',
            'city',
            'state',
            'zip',
            'address',
            'indicator',
            'class_commercial',
            'road_side_inspection',
            'date_issued',
            'vehicle_lic_no',
            'violation_id',
            'citation_no',
            'ticket_type',
            'court_name',
            'court_date',
            'court_address',
            'attorney_id',
            'attorney_response',
            'processor_name',
            'processor_email',
            'processor_ph_number',
            'processor_notes_to_attorney'
        ]));

        return redirect()->route(auth()->user()->portalRoutePrefix().'.tickets.edit', $ticket->id)->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
        $this->authorize('view', $ticket);
        return view('manager.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
        $this->authorize('update', $ticket);
        return view('manager.tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
        $this->authorize('update', $ticket);
        $request->merge([
            'class_commercial' => \request('class_commercial', 'No'),
            'road_side_inspection' => \request('road_side_inspection', 'No'),
        ]);
        $request->validate([
            'user_email' => 'required|email',
            'name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'address' => 'required',
            'indicator' => 'in:' . implode(',', Ticket::INDICATORS_ALLOWED),
            'class_commercial' => 'in:Yes,No',
            'road_side_inspection' => 'in:Yes,No',
            'date_issued' => 'required|date',
            'vehicle_lic_no' => 'required',
            'violation_id' => 'required|exists:violations,id',
            'citation_no' => '',
            'ticket_type' => '',
            'court_name' => '',
            'court_date' => '',
            'court_address' => '',
            'attorney_id' => 'exists:attorneys,id',
            'attorney_response' => 'in:Accepted,Rejected',
            'processor_name' => '',
            'processor_email' => '',
            'processor_ph_number' => '',
            'processor_notes_to_attorney' => '',
        ]);
        // Create a new Ticket record with the validated data
        $ticket->update($request->only([
            'user_email',
            'name',
            'company_id',
            'city',
            'state',
            'zip',
            'address',
            'indicator',
            'class_commercial',
            'road_side_inspection',
            'date_issued',
            'vehicle_lic_no',
            'violation_id',
            'citation_no',
            'ticket_type',
            'court_name',
            'court_date',
            'court_address',
            'attorney_id',
            'attorney_response',
            'processor_name',
            'processor_email',
            'processor_ph_number',
            'processor_notes_to_attorney',
        ]));

        return redirect()->route(auth()->user()->portalRoutePrefix().'.tickets.edit', $ticket->id)->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
