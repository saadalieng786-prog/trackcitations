<?php
namespace App\Services;

use App\Models\OutgoingLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function __construct(private LogService $logService = new LogService())
    {
    }

    public function send($phone, string $subject, string $message, $context = null): bool
    {
        try {
            $requestBody = [
                "contactPhone" => $phone,
                "accountPhone" => env('SIMPLE_SMS_ACCOUNT_PHONE'),
                "mode" => "AUTO",
                "subject" => $subject,
                "text" => $message,
            ];

            // Validate with pre-send
            $preSendResult = $this->preSend($requestBody['mode'], $requestBody['subject'], $requestBody['text']);
            if ($preSendResult === false) {
                Log::error("Pre-send SMS validation failed", $requestBody);
                return false;
            }

            // Actual send
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SIMPLE_SMS_API_KEY'),
                'Accept' => 'application/json',
            ])->post(env('SIMPLE_SMS_BASE_URL') . '/messages', $requestBody);

            if ($response->successful()) {
                $this->logService->storeOutgoingLog(OutgoingLog::TYPE_SMS, $context, json_encode($requestBody), json_encode($response->json()));
                Log::info("SMS sent successfully", ['phone' => $phone]);
                return true;
            }

            Log::error('SMS failed to send', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;

        } catch (\Exception $e) {
            Log::error('SMS exception', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function preSend(string $mode, string $subject, string $message): array|bool
    {
        try {
            $requestBody = [
                'mode' => $mode,
                'subject' => $subject,
                'text' => $message,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('SIMPLE_SMS_API_KEY'),
                'Accept' => 'application/json',
            ])->post(env('SIMPLE_SMS_BASE_URL') . '/messages/evaluate', $requestBody);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Pre-send validated successfully', $result);
                return $result;
            } else {
                Log::error('Pre-send validation failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Pre-send exception', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
