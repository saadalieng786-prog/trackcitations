<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class TicketAttachment extends Model
{
    //
    protected $fillable = ['filename', 'path', 'sf_id', 'description', 'sf_last_modified_date', 'last_modified_date',];

    protected $appends = ['url', 'preview_url', 'preview_type'];

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (blank($this->path)) {
            return null;
        }

        try {
            return route('ticket-attachments.download', $this);
        } catch (\Throwable) {
            if (filter_var($this->path, FILTER_VALIDATE_URL)) {
                return $this->path;
            }

            return URL::to($this->path);
        }
    }

    public function getPreviewUrlAttribute(): ?string
    {
        if (blank($this->path) || ! $this->id) {
            return null;
        }

        // Always same-origin preview stream (never the download/S3 redirect URL).
        return url('/ticket-attachments/'.$this->id.'/preview');
    }

    public function getPreviewTypeAttribute(): string
    {
        foreach ([(string) $this->filename, (string) $this->path] as $value) {
            $path = parse_url($value, PHP_URL_PATH) ?: $value;
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'doc', 'docx'], true)) {
                return $ext === 'jpeg' ? 'jpg' : $ext;
            }
        }

        $name = strtolower((string) $this->filename);
        if (str_contains($name, '.pdf') || str_ends_with($name, 'pdf')) {
            return 'pdf';
        }
        if (preg_match('/\.(jpe?g|png|gif|webp)\b/', $name) || preg_match('/\b(jpe?g|png|gif|webp)\b/', $name)) {
            return 'jpg';
        }

        return 'unknown';
    }
}
