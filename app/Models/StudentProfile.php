<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'education_level',
        'academic_marks',
        'interests',
        'logical_score',
        'problem_solving_score',
        'onboarding_completed',
    ];

    protected function casts(): array
    {
        return [
            'academic_marks' => 'array',
            'interests' => 'array',
            'onboarding_completed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
