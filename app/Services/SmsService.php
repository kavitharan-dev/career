<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class SmsService
{
    /**
     * Normalize to E.164 for Sri Lanka (+94 7XXXXXXXX).
     */
    public function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone) ?? '';

        if (str_starts_with($digits, '94')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return '+94'.$digits;
    }

    /**
     * Format for Text.lk API: 94771234567 (no + sign).
     */
    public function formatForTextLk(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (str_starts_with($digits, '94')) {
            return $digits;
        }

        return '94'.$digits;
    }

    public function isValidSriLankanMobile(string $phone): bool
    {
        $digits = preg_replace('/\D/', '', $phone) ?? '';

        if (str_starts_with($digits, '94')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return (bool) preg_match('/^7[0-9]{8}$/', $digits);
    }

    public function sendToPhone(string $phone, string $message): bool
    {
        if (! $this->isValidSriLankanMobile($phone)) {
            Log::warning('Invalid Sri Lankan phone number for SMS.', ['phone' => $phone]);

            return false;
        }

        $to = $this->normalizePhone($phone);

        if (! config('arivexa.sms.enabled')) {
            Log::channel('single')->info('[Arivexa SMS - log mode]', [
                'to' => $to,
                'message' => $message,
            ]);

            return true;
        }

        return match (config('arivexa.sms.driver', 'textlk')) {
            'twilio' => $this->sendViaTwilio($to, $message),
            default => $this->sendViaTextLk($phone, $message),
        };
    }

    protected function sendViaTextLk(string $phone, string $message): bool
    {
        $token = config('services.textlk.api_token');
        $senderId = config('services.textlk.sender_id');
        $endpoint = config('services.textlk.endpoint');
        $recipient = $this->formatForTextLk($phone);

        if (! $token || ! $senderId) {
            Log::warning('Text.lk is not configured. SMS not sent.', ['to' => $recipient]);

            return false;
        }

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->post($endpoint, [
                    'recipient' => $recipient,
                    'sender_id' => $senderId,
                    'type' => 'plain',
                    'message' => $message,
                ]);

            if ($response->successful() && $response->json('status') === true) {
                return true;
            }

            Log::error('Text.lk SMS failed', [
                'to' => $recipient,
                'status' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::error('Text.lk SMS send failed', [
                'to' => $recipient,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    protected function sendViaTwilio(string $to, string $message): bool
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');

        if (! $sid || ! $token || ! $from) {
            Log::warning('Twilio is not configured. SMS not sent.', ['to' => $to]);

            return false;
        }

        try {
            $client = new Client($sid, $token);
            $client->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Twilio SMS send failed', [
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function sendToUser(User $user, string $message): bool
    {
        if (! $user->phone) {
            return false;
        }

        return $this->sendToPhone($user->phone, $message);
    }

    public function sendRegistrationWelcome(User $user): bool
    {
        $message = sprintf(
            'Welcome to Arivexa, %s! Your account is registered. Complete your profile: %s',
            $user->name,
            route('onboarding.index')
        );

        return $this->sendToUser($user, $message);
    }

    public function sendLoginAlert(User $user): bool
    {
        $message = sprintf(
            'Arivexa login: Hi %s, you signed in at %s (Sri Lanka time). If this was not you, change your password.',
            $user->name,
            now()->timezone('Asia/Colombo')->format('d M Y, h:i A')
        );

        return $this->sendToUser($user, $message);
    }

    public function sendCareerReady(User $user, string $careerName, int $matchScore): bool
    {
        $message = sprintf(
            'Arivexa: Your career match is %s (%d%%). View your roadmap: %s',
            $careerName,
            $matchScore,
            route('dashboard')
        );

        return $this->sendToUser($user, $message);
    }

    public function sendTaskReminder(User $user, int $pendingCount): bool
    {
        $message = sprintf(
            'Arivexa reminder: You have %d pending learning task(s). Open your roadmap: %s',
            $pendingCount,
            route('roadmap')
        );

        return $this->sendToUser($user, $message);
    }
}
