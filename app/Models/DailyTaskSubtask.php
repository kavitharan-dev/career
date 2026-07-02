<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTaskSubtask extends Model
{
    protected $fillable = [
        'daily_task_id',
        'title',
        'sort_order',
        'is_completed',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
        ];
    }

    public function dailyTask(): BelongsTo
    {
        return $this->belongsTo(DailyTask::class);
    }
}
