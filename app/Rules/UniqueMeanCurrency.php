<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\MeanOfPayment;

class UniqueMeanCurrency implements Rule
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
        $IDs = request("cashMeans");
        $means = MeanOfPayment::whereIn("id", $IDs)->get();
        $currencies = [];

        foreach ($means as $mean) {
            if (in_array($mean->currency_id, $currencies)) {
                return false;
            }
            else {
                array_push($currencies, $mean->currency_id);
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The currency isn't unique.";
    }
}
