<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Attorney;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class AttorneyController extends Controller
{
    public function dashboard()
    {
        $tickets = Ticket::filterByRole(request()->user())->with('company')->limit(5)->get();

        return view('attorney.dashboard', compact('tickets'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (request()->ajax()) {
            $data = User::role('attorney');

            if (\request('search')['value']) {
                $search = request('search')['value'];

                // Add search conditions
                $data = $data->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('state', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Handle ordering
            if (request()->has('order')) {
                $columns = ['id', 'name', 'state', 'city', 'last_login_at']; // Adjust to match your table columns
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
                $item->last_login_at = $item->last_login_at ? \Carbon\Carbon::parse($item->last_login_at)->diffForHumans() : 'Never';
                $item->action = '<a href="'.route($portal.'.attorneys.edit', $item->roleable->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-edit text-xl leading-none"></i>
                                    </a>
                                    <form action="'.route($portal.'.attorneys.destroy', $item->roleable->id).'" method="POST" class="inline delete-attorney-form">
                                        <input type="hidden" name="_method" value="DELETE">
                                        '. csrf_field() .'
                                        <button href="#" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                            <i class="ti ti-trash text-xl leading-none"></i>
                                        </button>';
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


        return view('attorneys.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('attorneys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'dob' => 'nullable|date',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'phone' => 'sometimes|unique:users,phone',
            'timezone' => '',
            'notification_email' => '',
            'notification_sms' => '',
            'notification_push' => '',
            'office_hours_start' => 'date_format:H:i',
            'office_hours_end' => 'date_format:H:i|after:office_hours_start',
        ]);
        $request->merge([
            'notification_email' => $request->has('notification_email'),
            'notification_sms' => $request->has('notification_sms'),
            'notification_push' => $request->has('notification_push'),
        ]);

        $attorney = Attorney::create($request->only([
            'office_hours_start',
            'office_hours_end'
        ]));

        $user = $attorney->user()->create($request->only([
            'name',
            'email',
            'password',
            'dob',
            'address',
            'city',
            'state',
            'zip',
            'phone',
            'timezone',
            'notification_email',
            'notification_sms',
            'notification_push',
        ]));
        $user->assignRole('attorney');
        return redirect()->route(auth()->user()->portalRoutePrefix().'.attorneys.edit', $attorney->id)->with('success', 'Attorney created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attorney $attorney)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attorney $attorney)
    {
        //
        return view('attorneys.edit', compact('attorney'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attorney $attorney)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'dob' => 'nullable|date',
            'password' => 'nullable|string|min:8',
            'address' => '',
            'city' => '',
            'state' => '',
            'zip' => '',
            'phone' => '',
            'timezone' => '',
            'notification_email' => '',
            'notification_sms' => '',
            'notification_push' => '',
            'office_hours_start' => 'date_format:H:i',
            'office_hours_end' => 'date_format:H:i|after:office_hours_start',
        ]);
        $request->merge([
            'notification_email' => $request->has('notification_email'),
            'notification_sms' => $request->has('notification_sms'),
            'notification_push' => $request->has('notification_push'),
        ]);

        $data = $request->only(array_merge(
            [
                'name',
                'email',
                'dob',
                'address',
                'city',
                'state',
                'zip',
                'phone',
                'timezone',
                'notification_email',
                'notification_sms',
                'notification_push',
            ],
            $request->password ? ['password'] : []
        ));

        if ($request->password) {
            $data['password'] = bcrypt($request->password); // Hash the password
        }

        $attorney->user()->update($data);

        $attorney->update($request->only([
            'office_hours_start',
            'office_hours_end'
        ]));

        return redirect()->route(auth()->user()->portalRoutePrefix().'.attorneys.edit', $attorney->id)->with('success', 'Attorney updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attorney $attorney)
    {
        //
        if (!auth()->user()->isInternalAdmin()) {
            abort(403);
        }
        $attorney->user()->delete();

        $attorney->delete();
        return redirect()->route(auth()->user()->portalRoutePrefix().'.attorneys.index')->with('success', 'Attorney deleted successfully.');
    }
}
