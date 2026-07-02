<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoadmapStepTemplate extends Model
{
    protected $fillable = [
        'career_domain_id',
        'title',
        'level',
        'sort_order',
        'description',
    ];

    public function careerDomain(): BelongsTo
    {
        return $this->belongsTo(CareerDomain::class);
    }

    public function taskTemplates(): HasMany
    {
        return $this->hasMany(DailyTaskTemplate::class)->orderBy('day_number');
    }
}
