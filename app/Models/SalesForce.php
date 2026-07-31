<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesForce extends Model
{
    protected $fillable = [
        'sf_last_sync_time',
        'sf_att_last_sync_time',
        'sf_file_last_sync_time',
        'client_id',
        'client_secret',
        'redirect_uri',
        'login_uri',
        'sf_access_id',
        'sf_access_token',
        'sf_refresh_token',
        'sf_instance_url',
        'sf_signature',
        'sf_issued_at',
        'sf_account_activity_synced_at',
        'sf_contact_activity_synced_at',
        'status',
        'reason',
    ];

    public const STATUS_RUNNING = 1;
    public const STATUS_FINISHED = 0;
    public const STATUS_FAILED = 2;

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_RUNNING => 'Running',
            self::STATUS_FAILED => 'Failed',
            default => 'Finished',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_RUNNING => 'bg-primary-50 text-blue-700 ring-blue-700/10',
            self::STATUS_FAILED => 'bg-danger-50 text-red-700 ring-red-600/10',
            default => 'bg-success-50 text-green-700 ring-green-600/20',
        };
    }

    public function isConfigured(): bool
    {
        return filled($this->sf_access_token)
            && filled($this->sf_refresh_token)
            && filled($this->sf_instance_url);
    }
}
