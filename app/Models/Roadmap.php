<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Roadmap extends Model
{
    protected $fillable = [
        'user_id',
        'career_domain_id',
        'title',
        'completion_percentage',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function careerDomain(): BelongsTo
    {
        return $this->belongsTo(CareerDomain::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RoadmapStep::class)->orderBy('sort_order');
    }
}
