<?php

namespace App\Rules\Profile;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class SameAsCurrentPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Hash::check($value, auth()->user()->password);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Current password doesn't match our records.";
    }
}
