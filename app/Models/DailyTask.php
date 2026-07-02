<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyTask extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'roadmap_step_id',
        'title',
        'description',
        'day_number',
        'status',
        'completed_at',
    ];

    protected function casts(): array
    {
        return ['completed_at' => 'datetime'];
    }

    public function roadmapStep(): BelongsTo
    {
        return $this->belongsTo(RoadmapStep::class);
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(DailyTaskSubtask::class)->orderBy('sort_order');
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function allSubtasksCompleted(): bool
    {
        if ($this->subtasks()->doesntExist()) {
            return true;
        }

        return ! $this->subtasks()->where('is_completed', false)->exists();
    }

    public function subtaskProgress(): array
    {
        $total = $this->subtasks()->count();
        $done = $this->subtasks()->where('is_completed', true)->count();

        return ['done' => $done, 'total' => $total];
    }
}
