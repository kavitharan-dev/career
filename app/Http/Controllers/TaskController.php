<?php

namespace App\Http\Controllers;

use App\Models\DailyTask;
use App\Models\DailyTaskSubtask;
use App\Services\BadgeService;
use App\Services\NotificationService;
use App\Services\ProgressTrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected ProgressTrackingService $progress,
        protected NotificationService $notifications,
        protected BadgeService $badges,
    ) {}

    public function complete(Request $request, DailyTask $task): RedirectResponse
    {
        $this->progress->ensureTaskSubtasks($task);

        if (! $task->allSubtasksCompleted()) {
            return back()->withErrors([
                'task' => 'Complete every subtask first (read → practice → review), then the main task finishes automatically.',
            ]);
        }

        $this->progress->completeTask($task, $request->user());
        $this->notifications->syncReminders($request->user());
        $this->badges->syncFor($request->user());

        return back()->with('success', 'Task marked as complete!');
    }

    public function uncomplete(Request $request, DailyTask $task): RedirectResponse
    {
        $this->progress->uncompleteTask($task, $request->user());
        $this->notifications->syncReminders($request->user());

        return back()->with('success', 'Task marked as not done. You can redo the subtasks.');
    }

    public function toggleSubtask(Request $request, DailyTask $task, DailyTaskSubtask $subtask): RedirectResponse
    {
        if ($subtask->daily_task_id !== $task->id) {
            abort(404);
        }

        if (! $task->roadmapStep->is_unlocked) {
            return back()->withErrors(['task' => 'This step is locked. Complete the previous step first.']);
        }

        $this->progress->ensureTaskSubtasks($task);
        $this->progress->toggleSubtask($subtask, $request->user());
        $this->notifications->syncReminders($request->user());

        $task->refresh();

        if ($task->isCompleted()) {
            $this->badges->syncFor($request->user());

            return back()->with('success', 'All subtasks done — main task completed!');
        }

        return back();
    }
}
