<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobListing extends Model
{
    protected $fillable = [
        'career_domain_id',
        'title',
        'company',
        'location',
        'apply_url',
        'apply_note',
        'expires_at',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function careerDomain(): BelongsTo
    {
        return $this->belongsTo(CareerDomain::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_active', true)
            ->whereDate('expires_at', '>=', now()->toDateString());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->where('is_active', false)
                ->orWhereDate('expires_at', '<', now()->toDateString());
        });
    }

    public function isExpired(): bool
    {
        return ! $this->is_active || $this->expires_at->lt(now()->startOfDay());
    }
}
