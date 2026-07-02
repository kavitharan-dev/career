<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SriLankanMobile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $digits = preg_replace('/\D/', '', (string) $value);

        if ($digits === null || $digits === '') {
            $fail('Please enter a valid Sri Lankan mobile number.');

            return;
        }

        if (str_starts_with($digits, '94')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (! preg_match('/^7[0-9]{8}$/', $digits)) {
            $fail('Use a Sri Lankan mobile number starting with 07 (e.g. 0771234567 or +94771234567).');
        }
    }
}
