<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryMean implements Rule
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

        if ($attribute == "category_id") {
            return !!auth()->user()->categories->where("id", $value)->count();
        }
        return !!auth()->user()->meansOfPayment->where("id", $value)->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This category / mean of payment doesn\'t exist.';
    }
}
