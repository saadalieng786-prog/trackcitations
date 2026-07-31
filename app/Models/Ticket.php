<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use App\Models\Scopes\CompanyScope;

#[ObservedBy([TicketObserver::class])]
class Ticket extends Model
{
    protected static ?bool $ticketNotesTableExists = null;

    protected $appends = [
        'original_points_value',
        'final_points_value',
        'points_saved',
    ];

    protected $fillable = [
        'sf_id',
        'user_email',
        'name',
        'company_id',
        'address',
        'birthdate',
        'city',
        'state',
        'zip',
        'dl_number',
        'class_commercial',
        'vehicle_lic_no',
        'citation_type',
        'violation_id',
        'location_violation',
        'city_county_occurrence',
        'speed_approx',
        'arresting_officer_name',
        'note',
        'file',
        'path',
        'date_time',
        'indicator',
        'disposition__c',
        'confirmed__c',
        'canceled__c',
        'lawyer_email',
        'admin_note',
        'citation_no',
        'status',
        'updated_by',
        'court_date',
        'court_address',
        'court_phone',
        'ticket_dispo',
        'date_issued',
        'court_name',
        'county',
        'ticket_number',
        'attorney_id',
        'road_side_inspection',
        'road_side_inspection_results',
        'sales_agent',
        'fname',
        'lname',
        'sales_agent_name',
        'sales_agent_email',
        'sales_agent_id',
        'dataq_number__c',
        'roadside_inspection_number__c',
        'ticket_type',
        'beginning_fine_amount',
        'final_fine_amount',
        'processor_name',
        'processor_email',
        'processor_ph_number',
        'processor_notes_to_attorney',
        'total_dver_points__c',
        'total_dver_points_removed__c',
        'attorney_response',
        'is_approved',
    ];


    //
    public const INDICATOR_SENT_TO_ATTORNEY = 'Sent to Attorney';
    public const INDICATOR_PENDING = 'Pending';
    public const INDICATOR_RECEIVED = 'Received';
    public const INDICATOR_CONTINUED = 'Continued';
    public const INDICATOR_DISPOSED = 'Disposed';
    public const INDICATOR_CANCELLED = 'Canceled';
    public const INDICATOR_ASSIGNED_TO_ATTORNEY = 'Attorney Assigned';

    public const INDICATORS_ALLOWED = [
        self::INDICATOR_SENT_TO_ATTORNEY,
        self::INDICATOR_RECEIVED,
        self::INDICATOR_CONTINUED,
        self::INDICATOR_DISPOSED,
        self::INDICATOR_CANCELLED,
        self::INDICATOR_ASSIGNED_TO_ATTORNEY,
        self::INDICATOR_PENDING
    ];
    public const ATTORENY_RESPONSE_ACCEPTED = 'Accepted';
    public const ATTORENY_RESPONSE_REJECTED = 'Rejected';

    public const TICKET_STATUS_OPEN = 1;
    public const TICKET_STATUS_CLOSED = 2;
    public const TICKET_STATUS_ARCHIVED = 0;

    protected static function booted(): void
    {
        static::addGlobalScope(new CompanyScope('company_id'));
    }

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function driver(): HasOneThrough
    {
        return $this->hasOneThrough(
            Driver::class,  // Final model
            User::class,    // Intermediary model
            'email',        // Foreign key on the User table (matches Ticket.user_email)
            'id',           // Foreign key on the Driver table (matches User.userable_id)
            'user_email',   // Local key on the Ticket table
            'roleable_id'   // Local key on the User table (Driver's morphable ID)
        )->where('users.roleable_type', Driver::class); // Ensure morph type matches Driver
    }

    public function isDverDataq() {
        $dver = false;
        $dataq = false;
        if ($this->attachments) {
            foreach ($this->attachments as $attachment) {
                $dataq = $dataq || Str::contains($attachment->filename ?? '', 'dataq', ignoreCase: true);
                $dver = $dver || Str::contains($attachment->filename ?? '', 'dver', ignoreCase: true);
            }
        }
        return ["DVER" => $dver, "DATAQ" => $dataq];
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }
    public function notes()
    {
        if (auth()->check() && auth()->user()->isInternalAdmin()) {
            // Show all notes for admin roles
            return $this->hasMany(TicketNote::class);
        }
        // Show only public notes for the other role
        return $this->hasMany(TicketNote::class)->where('is_public', true);
    }

    public static function notesTableExists(): bool
    {
        if (self::$ticketNotesTableExists === null) {
            self::$ticketNotesTableExists = Schema::hasTable((new TicketNote())->getTable());
        }

        return self::$ticketNotesTableExists;
    }

    public function safeNotes(): Collection
    {
        if (!self::notesTableExists()) {
            return collect();
        }

        if ($this->relationLoaded('notes')) {
            return $this->getRelation('notes');
        }

        return $this->notes()->with('user')->get();
    }

    public function attorney() {
        return $this->belongsTo(Attorney::class, 'attorney_id', 'id');
    }

    public function violation()
    {
        return $this->belongsTo(Violation::class);
    }

    public function scopeFilter($query, $filters) {
        return $filters->apply($query);
    }

    public function scopeFilterByRole(Builder $query, $currentUser = null)
    {
        $currentUser = $currentUser ?? auth()->user();
        if (!$currentUser) {
            return $query;
        }

        if ($currentUser->isInternalAdmin()) {
            // Admin: No filtering needed
            return $query;
        } elseif ($currentUser->isCompanyAdmin()) {
            // Manager: Filter by associated companies
            return $query->whereIn('company_id', $currentUser->managedCompanyIds());
        } elseif ($currentUser->hasRole(User::ROLE_ATTORNEY)) {
            return $query->where('attorney_id', $currentUser->roleable?->id);
        } elseif ($currentUser->hasRole(User::ROLE_DRIVER)) {
            return $query->whereHas('driver', function ($q) use ($currentUser){
                $q->whereHas('user', function($userQuery) use ($currentUser) {
                    $userQuery->where('email', $currentUser->email);
                });
            });
        }

        return $query->whereRaw('1 = 0');
    }

    public function scopeApproved(Builder $query)
    {
        return $query->where('indicator', '!=', Ticket::INDICATOR_PENDING)->orWhereNull('indicator');
    }

    public function scopeActive(Builder $query)
    {
        return $query->whereNotIn('status', [Ticket::TICKET_STATUS_ARCHIVED, Ticket::TICKET_STATUS_CLOSED])->orWhereNull('status');;
    }

    protected function pointsSaved(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                $original = $this->original_points_value;
                $final = $this->final_points_value;

                if ($original === null || $final === null) {
                    return 0.0;
                }

                return max(0, $original - $final);
            }
        );
    }

    protected function originalPointsValue(): Attribute
    {
        return Attribute::make(
            get: fn (): ?float => $this->normalizePointsValue($this->total_dver_points__c)
        );
    }

    protected function finalPointsValue(): Attribute
    {
        return Attribute::make(
            get: fn (): ?float => $this->normalizePointsValue($this->total_dver_points_removed__c)
        );
    }

    protected function normalizePointsValue(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = preg_replace('/[^0-9.\-]/', '', (string) $value);

        return is_numeric($normalized) ? (float) $normalized : null;
    }

    public static function pointsSavedSql(?string $table = null): string
    {
        $prefix = $table ? $table.'.' : '';

        $original = self::normalizedPointsSql('total_dver_points__c', $table);
        $final = self::normalizedPointsSql('total_dver_points_removed__c', $table);

        return "GREATEST(0, {$original} - {$final})";
    }

    public static function normalizedPointsSql(string $column, ?string $table = null): string
    {
        $prefix = $table ? $table.'.' : '';

        return "COALESCE(CAST(NULLIF(REGEXP_REPLACE({$prefix}{$column}, '[^0-9.-]', ''), '') AS DECIMAL(10,2)), 0)";
    }
}
