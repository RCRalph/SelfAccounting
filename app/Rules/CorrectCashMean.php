<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectCashMean implements Rule
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
        return $value == null ||
            auth()->user()->meansOfPayment()
                ->where("currency_id", $this->currency->id)
                ->where("id", $value)
                ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cash mean of payment is invalid.';
    }
}
