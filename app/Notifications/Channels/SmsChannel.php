<?php

namespace App\Notifications\Channels;

use App\Services\SmsService;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function send($notifiable, Notification $notification)
    {
        if (!method_exists($notification, 'toSms')) {
            return;
        }

        $rawPhone = $notifiable->phone ?? null;
        if (! is_string($rawPhone) || trim($rawPhone) === '') {
            logger()->warning('Skipping SMS: notifiable has no phone number.', [
                'notifiable_type' => is_object($notifiable) ? get_class($notifiable) : gettype($notifiable),
                'notifiable_id' => is_object($notifiable) ? ($notifiable->id ?? null) : null,
                'notification' => get_class($notification),
            ]);

            return;
        }

        $message = $notification->toSms($notifiable);
        $phone = $this->normalizePhone($rawPhone);

        if ($phone === '') {
            logger()->warning('Skipping SMS: phone normalized to empty.', [
                'raw_phone' => $rawPhone,
                'notifiable_id' => $notifiable->id ?? null,
                'notification' => get_class($notification),
            ]);

            return;
        }

        $this->smsService->send($phone, $message, $message, $notification->ticket ?? null);

        logger()->info("Sending SMS to {$phone}: {$message}");
    }

    public function normalizePhone(?string $phone, array $stripCountryCodes = ['+1', '1', '0020', '+20']): string
    {
        if ($phone === null || trim($phone) === '') {
            return '';
        }

        // Remove all non-digits
        $digits = preg_replace('/\D+/', '', $phone) ?: '';

        // Check and remove known prefixes
        foreach ($stripCountryCodes as $code) {
            $codeDigits = preg_replace('/\D+/', '', $code);
            if ($codeDigits !== '' && str_starts_with($digits, $codeDigits)) {
                return substr($digits, strlen($codeDigits));
            }
        }

        return $digits;
    }

}
