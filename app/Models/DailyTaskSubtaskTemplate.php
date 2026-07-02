<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTaskSubtaskTemplate extends Model
{
    protected $fillable = [
        'daily_task_template_id',
        'title',
        'sort_order',
    ];

    public function taskTemplate(): BelongsTo
    {
        return $this->belongsTo(DailyTaskTemplate::class, 'daily_task_template_id');
    }
}
