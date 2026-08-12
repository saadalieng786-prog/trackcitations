<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\CompanyScope;

class Company extends Model
{
    protected $fillable = [
        'name',
        'parent_company_id',
        'ct_email',
        'ct_fname',
        'ct_lname',
        'dot',
        'sf_id',
    ];

    // protected static function booted(): void
    // {
    //     static::addGlobalScope(new CompanyScope('companies.id'));
    // }

    public function user()
    {
        return $this->morphOne(User::class, 'roleable');
    }

    public function managers()
    {
        return $this->belongsToMany(Manager::class)
            ->withPivot('is_write_access')
            ->withTimestamps();
    }

    public function parentCompany()
    {
        return $this->belongsTo(self::class, 'parent_company_id');
    }

    public function childCompanies()
    {
        return $this->hasMany(self::class, 'parent_company_id');
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function contacts() {
        return $this->hasMany(CompanyContact::class);
    }

    public function driversCount(): int
    {
        return Driver::withoutGlobalScopes()
            ->where('company_id', $this->id)
            ->count();
    }

    public function isParentCompany(): bool
    {
        return $this->childCompanies()->exists();
    }

    public function descendantCompanyIds(): array
    {
        $allIds = [$this->id];
        $frontier = [$this->id];

        while (! empty($frontier)) {
            $childIds = self::whereIn('parent_company_id', $frontier)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $frontier = array_values(array_diff($childIds, $allIds));
            $allIds = array_values(array_unique(array_merge($allIds, $frontier)));
        }

        return $allIds;
    }

    public function ancestorCompanies()
    {
        $ancestors = collect();
        $current = $this->parentCompany;

        while ($current) {
            $ancestors->push($current);
            $current = $current->parentCompany;
        }

        return $ancestors;
    }

    public function driversCountIncludingChildren(): int
    {
        return Driver::withoutGlobalScopes()
            ->whereIn('company_id', $this->descendantCompanyIds())
            ->count();
    }

    public function openTicketsCountIncludingChildren(): int
    {
        return Ticket::withoutGlobalScopes()
            ->whereIn('company_id', $this->descendantCompanyIds())
            ->active()
            ->count();
    }

    public function closedTicketsCountIncludingChildren(): int
    {
        return Ticket::withoutGlobalScopes()
            ->whereIn('company_id', $this->descendantCompanyIds())
            ->where('status', Ticket::TICKET_STATUS_CLOSED)
            ->count();
    }

    public function lifetimePointsSaved(): float
    {
        return (float) (Ticket::withoutGlobalScopes()
            ->whereIn('company_id', $this->descendantCompanyIds())
            ->selectRaw('COALESCE(SUM('.Ticket::pointsSavedSql().'), 0) as aggregate')
            ->value('aggregate') ?? 0);
    }
}
