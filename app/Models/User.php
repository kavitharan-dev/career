<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withPivot('proficiency')->withTimestamps();
    }

    public function careerRecommendations(): HasMany
    {
        return $this->hasMany(CareerRecommendation::class);
    }

    public function careerFitQuizzes(): HasMany
    {
        return $this->hasMany(UserCareerFitQuiz::class);
    }

    public function primaryRecommendation(): HasOne
    {
        return $this->hasOne(CareerRecommendation::class)->where('is_primary', true);
    }

    public function roadmaps(): HasMany
    {
        return $this->hasMany(Roadmap::class);
    }

    public function activeRoadmap(): HasOne
    {
        return $this->hasOne(Roadmap::class)->latestOfMany();
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(AppNotification::class);
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function badges(): HasMany
    {
        return $this->hasMany(UserBadge::class)->orderByDesc('earned_at');
    }

    public function hasCompletedOnboarding(): bool
    {
        return (bool) $this->studentProfile?->onboarding_completed;
    }

    public function ensureProfile(): StudentProfile
    {
        return $this->studentProfile()->firstOrCreate([]);
    }
}
