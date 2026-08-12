<?php
/*
 * Copyright © 2024 Mohamed A. Shehata (elza3ym@icloud.com)
 * All rights reserved.
 */

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_STAFF_ADMIN = 'staff_admin';
    public const ROLE_MANAGER = 'manager';
    public const ROLE_COMPANY_ADMIN = 'company_admin';
    public const ROLE_ATTORNEY = 'attorney';
    public const ROLE_DRIVER = 'driver';

    public static function internalAdminRoles(): array
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_SUPER_ADMIN,
            self::ROLE_STAFF_ADMIN,
        ];
    }

    public static function internalAdminRoleOptions(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_STAFF_ADMIN => 'Staff Admin',
            self::ROLE_ADMIN => 'Admin',
        ];
    }

    public static function companyAdminRoles(): array
    {
        return [
            self::ROLE_MANAGER,
            self::ROLE_COMPANY_ADMIN,
        ];
    }

    public static function companyAdminRoleOptions(): array
    {
        return [
            self::ROLE_COMPANY_ADMIN => 'Company Admin',
            self::ROLE_MANAGER => 'Legacy Manager',
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dob',
        'address',
        'city',
        'state',
        'zip',
        'phone',
        'timezone',
        'notification_email',
        'notification_sms',
        'notification_push',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roleable()
    {
        return $this->morphTo();
    }

    public function scopeFilter(Builder $query, $filters) {
        return $filters->apply($query);
    }

    public function scopeFilterByRole(Builder $query, $currentUser)
    {
        if ($currentUser->isInternalAdmin()) {
            // Admin: No filtering needed
            return $query;
        } elseif ($currentUser->isCompanyAdmin()) {
            // Manager: Filter by associated companies via pivot table
            $managerCompanyIds = $currentUser->managedCompanyIds();

            // Dynamically filter based on roleable type
            return $query->whereHasMorph(
                'roleable',
                [\App\Models\Driver::class, \App\Models\Manager::class],
                function ($q, $type) use ($managerCompanyIds) {
                    if ($type === \App\Models\Driver::class) {
                        $q->whereIn('company_id', $managerCompanyIds);
                    } elseif ($type === \App\Models\Manager::class) {
                        $q->whereHas('companies', function ($qu) use ($managerCompanyIds) {
                            $qu->whereIn('company_id', $managerCompanyIds);
                        });
                    }
                }
            );
        }

        // Default: No access
        return $query->whereRaw('1 = 0'); // This ensures no records are returned
    }

    public function isInternalAdmin(): bool
    {
        return $this->hasAnyRole(self::internalAdminRoles());
    }

    public function isCompanyAdmin(): bool
    {
        return $this->hasAnyRole(self::companyAdminRoles()) && $this->roleable instanceof Manager;
    }

    public function managedCompanyIds(): array
    {
        if (! $this->isCompanyAdmin()) {
            return [];
        }

        $allIds = $this->roleable->companies()->pluck('companies.id')->map(fn ($id) => (int) $id)->all();
        $frontier = $allIds;

        while (! empty($frontier)) {
            $childIds = Company::whereIn('parent_company_id', $frontier)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $frontier = array_values(array_diff($childIds, $allIds));
            $allIds = array_values(array_unique(array_merge($allIds, $frontier)));
        }

        return $allIds;
    }

    public function writableCompanyIds(): array
    {
        if (! $this->isCompanyAdmin()) {
            return [];
        }

        $allIds = $this->roleable->companies()
            ->wherePivot('is_write_access', true)
            ->pluck('companies.id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $frontier = $allIds;

        while (! empty($frontier)) {
            $childIds = Company::whereIn('parent_company_id', $frontier)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $frontier = array_values(array_diff($childIds, $allIds));
            $allIds = array_values(array_unique(array_merge($allIds, $frontier)));
        }

        return $allIds;
    }

    public function canAccessCompany(int $companyId): bool
    {
        return $this->isInternalAdmin() || in_array($companyId, $this->managedCompanyIds(), true);
    }

    public function canWriteCompany(int $companyId): bool
    {
        return $this->isInternalAdmin() || in_array($companyId, $this->writableCompanyIds(), true);
    }

    public function portalRoutePrefix(): string
    {
        foreach ([
            self::ROLE_SUPER_ADMIN,
            self::ROLE_STAFF_ADMIN,
            self::ROLE_ADMIN,
            self::ROLE_COMPANY_ADMIN,
            self::ROLE_MANAGER,
            self::ROLE_ATTORNEY,
            self::ROLE_DRIVER,
        ] as $role) {
            if ($this->hasRole($role)) {
                return $role;
            }
        }

        return self::ROLE_DRIVER;
    }

    /**
     * Primary role used for notification master switches (same priority as portal).
     */
    public function notificationRoleKey(): string
    {
        return $this->portalRoutePrefix();
    }

    public function inAppNotificationsEnabled(): bool
    {
        return NotificationRoleSetting::isEnabled($this->notificationRoleKey());
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)->latest();
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function readMessages()
    {
        return $this->hasMany(MessageRead::class);
    }

    public function unReadMessages()
    {
        return Message::whereHas('conversation', function ($query) {
            $query->whereHas('users',  function ($query) {
               $query->where('users.id', $this->id);
            });
        })->where('sender_id', '!=', $this->id) // Exclude messages sent by the same user
        ->whereDoesntHave('reads', function ($query) {
            $query->where('user_id', $this->id); // Check if the message has not been read by the user
        });
    }
}
