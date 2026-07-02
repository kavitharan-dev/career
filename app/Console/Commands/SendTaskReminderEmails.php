<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendTaskReminderEmails extends Command
{
    protected $signature = 'arivexa:send-reminders';

    protected $description = 'Send in-app and email reminders for pending learning tasks';

    public function handle(NotificationService $notifications): int
    {
        $count = 0;

        User::query()
            ->where('is_admin', false)
            ->whereHas('studentProfile', fn ($q) => $q->where('onboarding_completed', true))
            ->each(function (User $user) use ($notifications, &$count) {
                $notifications->syncReminders($user);
                $count++;
            });

        $this->info("Processed reminders for {$count} students.");

        return self::SUCCESS;
    }
}
