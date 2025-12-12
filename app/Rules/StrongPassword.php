<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 8) {
            $fail('Le mot de passe doit contenir au moins 8 caractères.');
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('Le mot de passe doit contenir au moins une majuscule.');
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('Le mot de passe doit contenir au moins une minuscule.');
        }

        if (!preg_match('/[0-9]/', $value)) {
            $fail('Le mot de passe doit contenir au moins un chiffre.');
        }

        if (!preg_match('/[@$!%*?&]/', $value)) {
            $fail('Le mot de passe doit contenir au moins un caractère spécial (@$!%*?&).');
        }
    }
}