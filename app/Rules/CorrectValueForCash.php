<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Cash;

class CorrectValueForCash implements Rule
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
        $id = explode(".", $attribute)[2];
        return Cash::where([
            "currency_id" => request("bundleData.cash.$id.currency_id"),
            "value" => $value
        ])->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Invalid value for given currency";
    }
}
