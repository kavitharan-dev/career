<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NameWithoutDigits implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match('/\d/', (string) $value)) {
            $fail('Name cannot contain numbers. Use letters only.');
        }
    }
}
