<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class UniqueEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $userExists = DB::table('users')->where('email', $value)->exists();

        $studentExists = DB::table('students')->where('email', $value)->exists();

        if ($userExists || $studentExists) {
            $fail('The email address has already been taken, please choose another email .');
        }
    }
}
