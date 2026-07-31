<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Filters\OutgoingLogsFilter;
use App\Models\Log;
use App\Models\OutgoingLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    //
    public function index()
    {
        $logs = Log::latest()->paginate(15);
        return view('admin.logs.index', compact('logs'));
    }

    public function outgoing(OutgoingLogsFilter $filters)
    {
        if (request()->ajax()) {
            $data = OutgoingLog::filter($filters);


            if (\request('search')['value']) {
                $search = request('search')['value'];

                // Add search conditions
                $data = $data->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%")
                        ->orWhere('request', 'like', "%{$search}%")
                        ->orWhere('response', 'like', "%{$search}%");
                });
            }

            // Pagination
            $length = request('length', 10);
            $start = request('start', 0);
            $page = ($start / $length) + 1;

            $data = $data->paginate($length, ['*'], 'page', $page);

            // Response
            return response()->json([
                'draw' => request()->get('draw'),
                'recordsTotal' => $data->total(),
                'recordsFiltered' => $data->total(),
                'data' => $data->items(),
            ]);
        }

        return view('admin.logs.outgoing');
    }
}
