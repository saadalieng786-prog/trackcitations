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

    protected $appends = ['url', 'preview_url'];

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
        if (blank($this->path)) {
            return null;
        }

        try {
            return route('ticket-attachments.preview', $this);
        } catch (\Throwable) {
            return $this->url;
        }
    }
}
