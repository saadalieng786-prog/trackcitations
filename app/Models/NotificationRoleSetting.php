<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class NotificationRoleSetting extends Model
{
    protected $fillable = [
        'role',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    public const CACHE_KEY = 'notification_role_settings';

    public static function roleLabels(): array
    {
        // Admin is linked to Staff Admin and is not shown separately in the UI.
        return [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_STAFF_ADMIN => 'Staff Admin',
            User::ROLE_MANAGER => 'Manager',
            User::ROLE_COMPANY_ADMIN => 'Company Admin',
            User::ROLE_ATTORNEY => 'Attorney',
            User::ROLE_DRIVER => 'Driver',
        ];
    }

    public static function defaults(): array
    {
        return [
            User::ROLE_SUPER_ADMIN => true,
            User::ROLE_STAFF_ADMIN => true,
            User::ROLE_ADMIN => true,
            User::ROLE_MANAGER => false,
            User::ROLE_COMPANY_ADMIN => false,
            User::ROLE_ATTORNEY => false,
            User::ROLE_DRIVER => true,
        ];
    }

    public static function allMapped(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $stored = static::query()->pluck('enabled', 'role')->map(fn ($v) => (bool) $v)->all();
            $mapped = [];

            foreach (self::defaults() as $role => $default) {
                $mapped[$role] = array_key_exists($role, $stored) ? (bool) $stored[$role] : $default;
            }

            // Admin always mirrors Staff Admin.
            $mapped[User::ROLE_ADMIN] = (bool) ($mapped[User::ROLE_STAFF_ADMIN] ?? false);

            return $mapped;
        });
    }

    public static function isEnabled(string $role): bool
    {
        $mapped = self::allMapped();

        if ($role === User::ROLE_ADMIN) {
            return (bool) ($mapped[User::ROLE_STAFF_ADMIN] ?? self::defaults()[User::ROLE_STAFF_ADMIN]);
        }

        if (array_key_exists($role, $mapped)) {
            return $mapped[$role];
        }

        return (bool) (self::defaults()[$role] ?? false);
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function syncFromRequest(array $rolesEnabled): void
    {
        // Keep Admin in sync with Staff Admin.
        $rolesEnabled[User::ROLE_ADMIN] = ! empty($rolesEnabled[User::ROLE_STAFF_ADMIN]);

        foreach (array_keys(self::defaults()) as $role) {
            static::updateOrCreate(
                ['role' => $role],
                ['enabled' => ! empty($rolesEnabled[$role])]
            );
        }

        self::clearCache();
    }

    /**
     * @param  iterable<User>  $users
     * @return Collection<int, User>
     */
    public static function filterEnabledUsers(iterable $users): Collection
    {
        return collect($users)->filter(fn ($user) => $user instanceof User && $user->inAppNotificationsEnabled())->values();
    }
}
