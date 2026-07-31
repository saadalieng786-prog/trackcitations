<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutgoingLog extends Model
{
    public const TYPE_SMS = "SMS";

    //
    public function sender()
    {
        return $this->morphTo();
    }

    public function context()
    {
        return $this->morphTo();
    }

    public function scopeFilter($query, $filters) {
        return $filters->apply($query);
    }

}
