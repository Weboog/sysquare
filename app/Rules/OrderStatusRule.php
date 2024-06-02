<?php

namespace App\Rules;

use App\Enums\OrderStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class OrderStatusRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $status_array = array_map(function (OrderStatus $case) {
            return $case->value;
        }, OrderStatus::cases());

        if (!in_array($value, $status_array)) {
            $fail('Status :attribute must be OrderStatus Enum');
        }
    }
}
