<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BackupValidQueryDate implements Rule
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
        $index1 = explode(".", $attribute)[2];
        $index2 = explode(".", $attribute)[4];
        $minDate = request("bundleData.reports.$index1.queries.$index2.min_date");
        $maxDate = request("bundleData.reports.$index1.queries.$index2.max_date");

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
        return 'Invalid report query dates.';
    }
}
