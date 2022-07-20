<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CashBelongsToCurrency implements Rule
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
        return $this->currency->cash()->where("currency_id", $this->currency->id)->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Given cash ID doesn't belong to given currency.";
    }
}
