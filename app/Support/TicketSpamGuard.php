<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class TicketSpamGuard
{
    public static function mathQuestion(): string
    {
        $left = random_int(2, 9);
        $right = random_int(1, 9);
        session(['ticket_math_sum' => $left + $right]);

        return "{$left} + {$right}";
    }

    public static function recaptchaEnabled(): bool
    {
        return filled(config('services.recaptcha.site_key'))
            && filled(config('services.recaptcha.secret_key'));
    }

    public static function verify(Request $request): void
    {
        if (filled($request->input('website'))) {
            throw ValidationException::withMessages([
                'math_answer' => 'Please complete the form and try again.',
            ]);
        }

        $expected = session('ticket_math_sum');
        $given = (int) $request->input('math_answer');

        if ($expected === null || $given !== (int) $expected) {
            throw ValidationException::withMessages([
                'math_answer' => 'Please solve the math problem to continue.',
            ]);
        }

        session()->forget('ticket_math_sum');

        if (! self::recaptchaEnabled()) {
            return;
        }

        $token = (string) $request->input('g-recaptcha-response');
        if ($token === '') {
            throw ValidationException::withMessages([
                'math_answer' => 'Please refresh the page and try submitting again.',
            ]);
        }

        $response = Http::asForm()->timeout(8)->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        $score = (float) ($response->json('score') ?? 0);
        $success = (bool) $response->json('success');
        $minScore = (float) config('services.recaptcha.min_score', 0.5);

        if (! $success || $score < $minScore) {
            throw ValidationException::withMessages([
                'math_answer' => 'Submission could not be verified. Please try again.',
            ]);
        }
    }
}
