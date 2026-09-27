<?php

namespace App\Support;

use Illuminate\Validation\Rules\Password;

class SecurityPolicy
{
    /**
     * Get the unified strong password validation rules.
     * Enforces min 8 characters, letters, mixed case, numbers, and symbols.
     *
     * @return list<mixed>
     */
    public static function passwordRules(bool $confirmed = true): array
    {
        $passwordRule = Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols();

        $rules = [
            'required',
            'string',
            $passwordRule,
        ];

        if ($confirmed) {
            $rules[] = 'confirmed';
        }

        return $rules;
    }
}
