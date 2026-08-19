<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Http\Controllers;

use App\Models\TicketAttachment;
use App\Support\AttachmentStorage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    use AuthorizesRequests;

    public function preview(TicketAttachment $ticketAttachment)
    {
        [$disk, $relativePath, $filename] = $this->resolveAttachmentFile($ticketAttachment);

        return Storage::disk($disk)->response(
            $relativePath,
            $filename,
            [],
            'inline'
        );
    }

    public function download(TicketAttachment $ticketAttachment)
    {
        [$disk, $relativePath, $filename] = $this->resolveAttachmentFile($ticketAttachment);

        if (config("filesystems.disks.{$disk}.driver") === 's3') {
            return redirect()->away(
                Storage::disk($disk)->temporaryUrl($relativePath, now()->addMinutes(30))
            );
        }

        return Storage::disk($disk)->response(
            $relativePath,
            $filename,
            [],
            'attachment'
        );
    }

    protected function resolveAttachmentFile(TicketAttachment $ticketAttachment): array
    {
        $ticketAttachment->loadMissing('ticket');
        abort_unless($ticketAttachment->ticket, 404);
        $this->authorize('view', $ticketAttachment->ticket);

        $relativePath = AttachmentStorage::relativePathFromStoredPath($ticketAttachment->path);
        if (blank($relativePath)) {
            abort(404, 'Attachment path is missing.');
        }

        $filename = $ticketAttachment->filename ?: basename($relativePath);

        $disksToTry = array_values(array_unique(array_filter([
            AttachmentStorage::ticketDisk(),
            AttachmentStorage::messageDisk(),
            's3',
            'public',
            'local',
        ])));

        foreach ($disksToTry as $tryDisk) {
            try {
                if (! config("filesystems.disks.{$tryDisk}")) {
                    continue;
                }

                if (Storage::disk($tryDisk)->exists($relativePath)) {
                    return [$tryDisk, $relativePath, $filename];
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if (filter_var($ticketAttachment->path, FILTER_VALIDATE_URL)) {
            abort(404, 'Attachment file was not found in storage.');
        }

        abort(404, 'Attachment file was not found in storage.');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketAttachment $ticketAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TicketAttachment $ticketAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketAttachment $ticketAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketAttachment $ticketAttachment)
    {
        //
    }
}
