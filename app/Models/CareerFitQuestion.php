<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerFitQuestion extends Model
{
    protected $fillable = [
        'career_domain_id',
        'question_key',
        'question_text',
        'options',
        'sort_order',
    ];

    protected function casts(): array
    {
        return ['options' => 'array'];
    }

    public function careerDomain(): BelongsTo
    {
        return $this->belongsTo(CareerDomain::class);
    }
}
