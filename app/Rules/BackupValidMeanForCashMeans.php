<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BackupValidMeanForCashMeans implements Rule
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
        $index = explode(".", $attribute)[2];

        $currency = request("extensionData.cashMeans.$index.currency_id");
        $value--;
        $mean = request("means.$value");

        return $currency == $mean["currency_id"];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Invalid mean id for given currency (cash means).";
    }
}
