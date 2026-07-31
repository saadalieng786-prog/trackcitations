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
        $message = $notification->toSms($notifiable);
        $phone = $this->normalizePhone($notifiable->phone);

        $this->smsService->send($phone, $notification->toSms($notifiable), $message, $notification->ticket);

        logger()->info("Sending SMS to {$notifiable->phone}: {$message}");
    }

    public function normalizePhone(string $phone, array $stripCountryCodes = ['+1', '1', '0020', '+20']): string
    {
        // Remove all non-digits
        $digits = preg_replace('/\D+/', '', $phone);

        // Check and remove known prefixes
        foreach ($stripCountryCodes as $code) {
            $codeDigits = preg_replace('/\D+/', '', $code);
            if (str_starts_with($digits, $codeDigits)) {
                return substr($digits, strlen($codeDigits));
            }
        }

        return $digits;
    }

}
