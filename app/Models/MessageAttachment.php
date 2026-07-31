<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['message_id', 'file_path', 'file_name'];

    protected $appends = ['url'];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function getUrlAttribute(): ?string
    {
        if (blank($this->file_path)) {
            return null;
        }

        if (filter_var($this->file_path, FILTER_VALIDATE_URL)) {
            return $this->file_path;
        }

        return URL::to($this->file_path);
    }
}
