<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    public function __construct(
        private bool $requireInternational = false,
        private int $minDigits = 7,
        private int $maxDigits = 15
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) && ! is_numeric($value)) {
            $fail(__('validation.phone', ['attribute' => $attribute]));

            return;
        }

        $phone = (string) $value;

        // Only allow a single leading plus for international numbers
        if (str_contains($phone, '+')) {
            if (str_starts_with($phone, '+') === false || substr_count($phone, '+') > 1) {
                $fail(__('validation.phone', ['attribute' => $attribute]));

                return;
            }
        }

        // Strip allowed separators and plus sign for digit counting
        $normalized = preg_replace('/[\s\-\.\(\)\+]/', '', $phone);

        if ($normalized === null || ! ctype_digit($normalized)) {
            $fail(__('validation.phone', ['attribute' => $attribute]));

            return;
        }

        $length = strlen($normalized);
        if ($length < $this->minDigits || $length > $this->maxDigits) {
            $fail(__('validation.phone', ['attribute' => $attribute]));

            return;
        }

        if ($this->requireInternational && ! str_starts_with($phone, '+')) {
            $fail(__('validation.phone_international', ['attribute' => $attribute]));
        }
    }
}
