<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Company;
use App\Notifications\TicketNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Support\AttachmentStorage;
use App\Support\TicketSpamGuard;
use function Symfony\Component\String\s;

class HomeController extends Controller
{
    //
    public function homepage()
    {
        $violations = \App\Models\Violation::all();
        $mathQuestion = TicketSpamGuard::mathQuestion();
        $recaptchaSiteKey = TicketSpamGuard::recaptchaEnabled()
            ? config('services.recaptcha.site_key')
            : null;

        return view('homepage', compact('violations', 'mathQuestion', 'recaptchaSiteKey'));
    }

    public function submit(Request $request)
    {
        //
        $request->validate([
            'user_email' => 'required|email',
            'phone' => 'required|string|max:30',
            'name' => 'required',
            'company_name' => '',
            'city' => 'required',
            'state' => 'required',
            'date_issued' => 'required|date',
            'vehicle_lic_no' => 'required',
            'violation_id' => 'required|exists:violations,id',
            'citation_no' => '',
            'math_answer' => 'required|integer',
            'attachments'   => 'nullable|array',
            'attachments.*' => 'file|max:10240|mimes:doc,docx,pdf,png,jpg,heic'
        ]);

        TicketSpamGuard::verify($request);

        $request->merge([
            'indicator' => Ticket::INDICATOR_PENDING
        ]);

        if ($request->has('company_name')) {
            $request->merge([
                'name' => $request->name . " ( ". s($request->company_name). " ) "
            ]);
        }

        // Create a new Ticket record with the validated data
        $ticket = Ticket::create($request->only([
            'user_email',
            'phone',
            'name',
            'citation_no',
            'violation_id',
            'date_issued',
            'state',
            'city',
            'vehicle_lic_no',
            'indicator'
        ]));

        if (Ticket::notesTableExists()) {
            $ticket->notes()->create([
                'note' => request('description'),
                'is_public' => true
            ]);
        }

        $attachments = $request->file('attachments', []);
        foreach ($attachments as $attachment) {
            $stored = AttachmentStorage::storeTicketUpload($attachment);
            $ticket->attachments()->create([
                'filename' => $attachment->getClientOriginalName(),
                'path' => $stored['url'],
            ]);
        }

        $admins = User::role(User::ROLE_ADMIN)->get()
            ->merge(User::role(User::ROLE_SUPER_ADMIN)->get())
            ->merge(User::role(User::ROLE_STAFF_ADMIN)->get())
            ->unique('id');
        Notification::send($admins, new TicketNotification($ticket, 'created'));
        return redirect()->route('homepage')->with('success', 'Ticket submitted successfully.');
    }

    public function setSessionCompanies(Request $request)
    {
        $ids = $request->input('company_ids', []);
        $user = $request->user();

        if ($user->isInternalAdmin()) {
            $filteredIds = $ids;
        } elseif ($user->isCompanyAdmin()) {
            $validIds = $user->managedCompanyIds();

            // Filter selected IDs to only those the manager is allowed to access
            $filteredIds = array_filter($ids, fn($id) => in_array($id, $validIds));
        } else {
            // Default to no companies selected for other roles
            $filteredIds = [];
        }
        session(['active_company_ids' => $filteredIds]);

        return redirect()->back()->with('success', 'Selected companies updated.');
    }

}
