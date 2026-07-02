<?php

namespace App\Services;

use App\Mail\LoginAlertMail;
use App\Mail\RegistrationWelcomeMail;
use App\Mail\TaskReminderMail;
use App\Mail\WelcomeOnboardingMail;
use App\Models\AppNotification;
use App\Models\CareerRecommendation;
use App\Models\DailyTask;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function __construct(protected SmsService $sms) {}

    public function sendRegistrationNotifications(User $user): void
    {
        $userId = $user->id;

        defer(function () use ($userId): void {
            $user = User::find($userId);

            if (! $user) {
                return;
            }

            try {
                Mail::to($user->email)->send(new RegistrationWelcomeMail($user));
            } catch (\Throwable $e) {
                Log::warning('Registration email failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            app(SmsService::class)->sendRegistrationWelcome($user);
        });
    }

    public function sendLoginNotifications(User $user, ?string $ipAddress = null): void
    {
        $userId = $user->id;
        $loginTime = now()->format('d M Y, h:i A');

        defer(function () use ($userId, $loginTime, $ipAddress): void {
            $user = User::find($userId);

            if (! $user) {
                return;
            }

            try {
                Mail::to($user->email)->send(new LoginAlertMail($user, $loginTime, $ipAddress));
            } catch (\Throwable $e) {
                Log::warning('Login email failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            app(SmsService::class)->sendLoginAlert($user);
        });
    }

    public function syncReminders(User $user): void
    {
        $pendingCount = DailyTask::query()
            ->where('status', DailyTask::STATUS_PENDING)
            ->whereHas('roadmapStep', function ($q) use ($user) {
                $q->where('is_unlocked', true)
                    ->whereHas('roadmap', fn ($r) => $r->where('user_id', $user->id));
            })
            ->count();

        if ($pendingCount === 0) {
            return;
        }

        $exists = AppNotification::query()
            ->where('user_id', $user->id)
            ->where('type', 'pending_tasks')
            ->whereNull('read_at')
            ->whereDate('created_at', today())
            ->exists();

        if ($exists) {
            return;
        }

        AppNotification::create([
            'user_id' => $user->id,
            'type' => 'pending_tasks',
            'title' => 'Tasks waiting for you',
            'message' => "You have {$pendingCount} pending task(s) in your learning roadmap. Complete them to unlock the next level.",
        ]);

        $userId = $user->id;

        defer(function () use ($userId, $pendingCount): void {
            $user = User::find($userId);

            if (! $user) {
                return;
            }

            try {
                Mail::to($user->email)->send(new TaskReminderMail($user, $pendingCount));
            } catch (\Throwable $e) {
                Log::warning('Task reminder email failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            app(SmsService::class)->sendTaskReminder($user, $pendingCount);
        });
    }

    public function notifyImprovement(User $user, string $title, string $message): void
    {
        AppNotification::create([
            'user_id' => $user->id,
            'type' => 'improvement',
            'title' => $title,
            'message' => $message,
        ]);
    }

    public function sendWelcomeEmail(User $user, CareerRecommendation $recommendation): void
    {
        $userId = $user->id;
        $recommendationId = $recommendation->id;

        defer(function () use ($userId, $recommendationId): void {
            $user = User::find($userId);
            $recommendation = CareerRecommendation::with('careerDomain')->find($recommendationId);

            if (! $user || ! $recommendation) {
                return;
            }

            try {
                Mail::to($user->email)->send(new WelcomeOnboardingMail(
                    $user,
                    $recommendation->careerDomain->name,
                    $recommendation->match_score
                ));
            } catch (\Throwable $e) {
                Log::warning('Welcome email failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            app(SmsService::class)->sendCareerReady(
                $user,
                $recommendation->careerDomain->name,
                $recommendation->match_score
            );
        });
    }
}
