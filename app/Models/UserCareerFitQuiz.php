<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCareerFitQuiz extends Model
{
    public const VERDICT_STRONG = 'strong_fit';

    public const VERDICT_MODERATE = 'moderate_fit';

    public const VERDICT_LOW = 'low_fit';

    protected $fillable = [
        'user_id',
        'career_domain_id',
        'answers',
        'fit_score',
        'verdict',
        'verdict_summary',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function careerDomain(): BelongsTo
    {
        return $this->belongsTo(CareerDomain::class);
    }

    public function verdictLabel(): string
    {
        return match ($this->verdict) {
            self::VERDICT_STRONG => 'Strong fit — this career suits you',
            self::VERDICT_MODERATE => 'Moderate fit — you can succeed with training',
            default => 'Low fit — consider other careers first',
        };
    }
}
