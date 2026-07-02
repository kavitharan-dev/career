<?php

namespace App\Console\Commands;

use App\Mail\LoginAlertMail;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestNotificationsCommand extends Command
{
    protected $signature = 'arivexa:test-notifications {email? : Student email to test}';

    protected $description = 'Send a test email and SMS to verify Mail + Twilio configuration';

    public function handle(SmsService $sms): int
    {
        $email = $this->argument('email');

        $user = $email
            ? User::where('email', $email)->first()
            : User::where('is_admin', false)->whereNotNull('phone')->first();

        if (! $user) {
            $this->error('No student found. Register first or pass an email: php artisan arivexa:test-notifications student@example.com');

            return self::FAILURE;
        }

        $this->info("Testing notifications for: {$user->name} <{$user->email}> {$user->phone}");

        try {
            Mail::to($user->email)->send(new LoginAlertMail(
                $user,
                now()->timezone('Asia/Colombo')->format('d M Y, h:i A').' (SLST)',
                '127.0.0.1'
            ));
            $this->line('Email: sent (check inbox or Mailtrap inbox).');
        } catch (\Throwable $e) {
            $this->error('Email failed: '.$e->getMessage());
        }

        $ok = $sms->sendToUser($user, 'Arivexa test SMS: your notification setup is working.');

        if (config('arivexa.sms.enabled')) {
            $this->line($ok ? 'SMS: sent via Twilio.' : 'SMS: failed — check Twilio credentials and verified numbers.');
        } else {
            $this->line('SMS: logged to storage/logs/laravel.log (set SMS_ENABLED=true for real SMS).');
        }

        $this->newLine();
        $this->comment('Mail driver: '.config('mail.default'));
        $this->comment('SMS enabled: '.(config('arivexa.sms.enabled') ? 'yes' : 'no'));

        return self::SUCCESS;
    }
}
