<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoadmapStep extends Model
{
    protected $fillable = [
        'roadmap_id',
        'title',
        'level',
        'sort_order',
        'description',
        'is_unlocked',
    ];

    protected function casts(): array
    {
        return ['is_unlocked' => 'boolean'];
    }

    public function roadmap(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class);
    }

    public function dailyTasks(): HasMany
    {
        return $this->hasMany(DailyTask::class)->orderBy('day_number');
    }
}
