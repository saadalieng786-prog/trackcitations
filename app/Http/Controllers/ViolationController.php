<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (request()->ajax()) {
            $data = Violation::query();

            if (\request('search')['value']) {
                $search = request('search')['value'];

                // Add search conditions
                $data = $data->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%")
                        ->orWhere('violation', 'like', "%{$search}%");
                });
            }

            // Handle ordering
            if (request()->has('order')) {
                $columns = ['id', 'violation']; // Adjust to match your table columns
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
                $item->action = '<a href="'.route($portal.'.violations.edit', $item->id).'" class="w-8 h-8 rounded-xl inline-flex items-center justify-center btn-link-secondary">
                                        <i class="ti ti-edit text-xl leading-none"></i>
                                    </a>
                                    <form action="'.route($portal.'.violations.destroy', $item->id).'" method="POST" class="inline delete-citation-form">
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
        return view('violations.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('violations.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'violation' => 'required'
        ]);

        $violation = Violation::create($request->only(['violation']));

        return redirect()->route(auth()->user()->portalRoutePrefix().'.violations.edit', $violation->id)->with('success', 'Citation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Violation $violation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Violation $violation)
    {
        //
        return view('violations.edit', compact('violation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Violation $violation)
    {
        //
        $request->validate([
            'violation' => 'required'
        ]);

        $violation->violation = $request->violation;
        $violation->save();

        return redirect()->route(auth()->user()->portalRoutePrefix().'.violations.edit', $violation->id)->with('success', 'Citation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Violation $violation)
    {
        //
        $violation->delete();
        return redirect()->route(auth()->user()->portalRoutePrefix().'.violations.index', $violation->id)->with('success', 'Citation deleted successfully.');
    }
}
