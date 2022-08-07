<?php

namespace App\Rules\Extensions\Cash;

use Illuminate\Contracts\Validation\Rule;

use App\Cash;

class CorrectCashCurrency implements Rule
{
    private $currency;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($currency)
    {
        $this->currency = $currency;
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
        return Cash::find($value)->currency_id == $this->currency->id;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Cash ID doesn't belong to given currency.";
    }
}
