<?php

namespace App\Rules\IO;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrAccountUpdate implements Rule
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
        if ($value == null) {
            return true;
        }

        return (str_contains($attribute, "category") ? auth()->user()->categories() : auth()->user()->accounts())
            ->where("currency_id", request("currency_id"))
            ->where("id", $value)
            ->where("used_in_" . request()->type, true)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "This category / account doesn't exist.";
    }
}
