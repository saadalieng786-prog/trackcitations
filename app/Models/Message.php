<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['conversation_id', 'sender_id', 'content'];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

    public function reads()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function isReadByAnyone()
    {
        return !$this->reads->isEmpty();
    }

    public function isReadByUser(User $user)
    {
        return $this->reads()->where('user_id', $user->id)->exists();
    }
}
