<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Mail\supportRequested;
use App\Models\SupportSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    //
    public function index()
    {
        return view('support');
    }

    public function store(Request $request)
    {
        $request->validate([
           'subject' => 'required|string',
           'description' => 'required|string|min:10'
        ]);

        $recipients = SupportSetting::current()->recipientEmailList();

        if ($recipients !== []) {
            Mail::to($recipients)->send(new supportRequested(
                $request->subject,
                $request->description,
                auth()->user()->name
            ));
        }

        return redirect()->route('support.index')->with(['success' => 'Received support request successfully.']);
    }
}
