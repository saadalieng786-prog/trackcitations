<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SupportSetting extends Model
{
    protected $fillable = [
        'recipient_emails',
    ];

    public const CACHE_KEY = 'support_settings';

    public static function current(): self
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return static::query()->first() ?? static::query()->create(['recipient_emails' => null]);
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return list<string>
     */
    public function recipientEmailList(): array
    {
        $raw = (string) ($this->recipient_emails ?? '');

        if (trim($raw) === '') {
            return [];
        }

        $parts = preg_split('/[\s,;]+/', $raw) ?: [];

        return collect($parts)
            ->map(fn ($email) => strtolower(trim($email)))
            ->filter(fn ($email) => $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

    public function hasCustomRecipients(): bool
    {
        return count($this->recipientEmailList()) > 0;
    }
}
