<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    protected $fillable = ['name', 'slug', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('proficiency')->withTimestamps();
    }

    public static function slugFromName(string $name, ?int $userId = null): string
    {
        $base = Str::slug($name);
        $slug = $userId ? "{$base}-u{$userId}" : $base;
        $count = 0;

        while (static::query()->where('slug', $slug)->exists()) {
            $count++;
            $slug = $userId ? "{$base}-u{$userId}-{$count}" : "{$base}-{$count}";
        }

        return $slug;
    }
}
