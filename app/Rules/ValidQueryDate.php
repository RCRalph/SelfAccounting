<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidQueryDate implements Rule
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
        $index = explode(".", $attribute)[1];
        $minDate = request("queries.$index.min_date");
        $maxDate = request("queries.$index.max_date");

        if ($minDate == null || $maxDate == null) {
            return true;
        }

        return strtotime($minDate) <= strtotime($maxDate);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Min date is after max date";
    }
}
