<?php

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
        $mime = $this->guessMimeType($disk, $relativePath, $filename, $ticketAttachment);

        // Keep a usable download/preview name when Salesforce Title has no extension.
        $downloadName = $filename;
        if (! pathinfo($downloadName, PATHINFO_EXTENSION)) {
            $ext = match (true) {
                str_contains($mime, 'pdf') => 'pdf',
                str_contains($mime, 'jpeg') => 'jpg',
                str_contains($mime, 'png') => 'png',
                str_contains($mime, 'gif') => 'gif',
                str_contains($mime, 'webp') => 'webp',
                default => null,
            };
            if ($ext) {
                $downloadName .= '.'.$ext;
            }
        }

        return Storage::disk($disk)->response(
            $relativePath,
            $downloadName,
            [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="'.$downloadName.'"',
            ],
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

                if ($this->diskHasFile($tryDisk, $relativePath)) {
                    return [$tryDisk, $relativePath, $filename];
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        abort(404, 'Attachment file was not found in storage.');
    }

    protected function diskHasFile(string $disk, string $relativePath): bool
    {
        if (Storage::disk($disk)->exists($relativePath)) {
            return true;
        }

        if (config("filesystems.disks.{$disk}.driver") !== 's3') {
            return false;
        }

        try {
            return Storage::disk($disk)->size($relativePath) > 0;
        } catch (\Throwable) {
            return false;
        }
    }

    protected function guessMimeType(string $disk, string $relativePath, string $filename, TicketAttachment $attachment): string
    {
        $type = $attachment->preview_type;
        $map = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];
        if (isset($map[$type])) {
            return $map[$type];
        }

        try {
            $mime = Storage::disk($disk)->mimeType($relativePath);
            if (is_string($mime) && $mime !== '' && $mime !== 'application/octet-stream' && $mime !== 'binary/octet-stream') {
                return $mime;
            }
        } catch (\Throwable) {
        }

        try {
            $stream = Storage::disk($disk)->readStream($relativePath);
            if (is_resource($stream)) {
                $header = fread($stream, 16) ?: '';
                if (is_resource($stream)) {
                    fclose($stream);
                }
                if (str_starts_with($header, '%PDF')) {
                    return 'application/pdf';
                }
                if (str_starts_with($header, "\xFF\xD8\xFF")) {
                    return 'image/jpeg';
                }
                if (str_starts_with($header, "\x89PNG")) {
                    return 'image/png';
                }
                if (str_starts_with($header, 'GIF8')) {
                    return 'image/gif';
                }
            }
        } catch (\Throwable) {
        }

        return 'application/octet-stream';
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
