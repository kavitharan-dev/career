<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CareerDomain extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'salary_range',
        'job_outlook',
        'education_path',
        'typical_roles',
        'key_skills',
        'day_in_life',
        'sri_lanka_context',
        'who_should_choose',
        'sri_lanka_jobs',
        'trait_weights',
        'required_skill_slugs',
    ];

    protected function casts(): array
    {
        return [
            'trait_weights' => 'array',
            'required_skill_slugs' => 'array',
            'typical_roles' => 'array',
            'key_skills' => 'array',
            'sri_lanka_jobs' => 'array',
        ];
    }

    public function fitQuestions(): HasMany
    {
        return $this->hasMany(CareerFitQuestion::class)->orderBy('sort_order');
    }

    public function stepTemplates(): HasMany
    {
        return $this->hasMany(RoadmapStepTemplate::class)->orderBy('sort_order');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(CareerRecommendation::class);
    }

    public function jobListings(): HasMany
    {
        return $this->hasMany(JobListing::class);
    }
}
