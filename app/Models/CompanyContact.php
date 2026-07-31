<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyContact extends Model
{
    //
    protected $fillable = [
        'name',
        'email',
        'phone',
        'cell',
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }
}
