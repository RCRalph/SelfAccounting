<?php

namespace App\Rules\IO;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrMeanUpdate implements Rule
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

        return (str_contains($attribute, "category") ? auth()->user()->categories() : auth()->user()->meansOfPayment())
            ->where("currency_id", request("currency_id"))
            ->where("id", $value)
            ->where(request()->type . "_" . (str_contains($attribute, "category") ? "category" : "mean"), true)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "This category / mean of payment doesn't exist.";
    }
}
