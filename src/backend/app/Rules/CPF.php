<?php

namespace App\Rules;

use App\Entities\Types\CPF as CPFType;
use Closure;
use DomainException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CPF implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            new CPFType($value);
        } catch (DomainException $e) {
            $fail('O campo :attribute não contém um CPF válido.');
        }
    }
}
