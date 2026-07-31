<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Scopes\CompanyScope;

class Driver extends Model
{
    protected $fillable = ['company_id', 'sf_id'];
    //

    protected static function booted(): void
    {
        static::addGlobalScope(new CompanyScope('company_id'));
    }

    public function user()
    {
        return $this->morphOne(User::class, 'roleable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(
            Ticket::class,
            User::class,
            'roleable_id',
            'user_email',
            'id',
            'email'
        )->where('users.roleable_type', self::class);
    }

    public function openTicketsCount(): int
    {
        return (clone $this->tickets())->active()->count();
    }

    public function closedTicketsCount(): int
    {
        return (clone $this->tickets())
            ->where('status', Ticket::TICKET_STATUS_CLOSED)
            ->count();
    }

    public function lifetimePointsSaved(): float
    {
        return (float) ((clone $this->tickets())
            ->selectRaw('COALESCE(SUM('.Ticket::pointsSavedSql('tickets').'), 0) as aggregate')
            ->value('aggregate') ?? 0);
    }
}
