<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyTaskTemplate extends Model
{
    protected $fillable = [
        'roadmap_step_template_id',
        'title',
        'description',
        'day_number',
    ];

    public function stepTemplate(): BelongsTo
    {
        return $this->belongsTo(RoadmapStepTemplate::class, 'roadmap_step_template_id');
    }

    public function subtaskTemplates(): HasMany
    {
        return $this->hasMany(DailyTaskSubtaskTemplate::class)->orderBy('sort_order');
    }
}
