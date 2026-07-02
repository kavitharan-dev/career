<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerRecommendation extends Model
{
    protected $fillable = [
        'user_id',
        'career_domain_id',
        'match_score',
        'reasoning',
        'is_primary',
    ];

    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function careerDomain(): BelongsTo
    {
        return $this->belongsTo(CareerDomain::class);
    }
}
