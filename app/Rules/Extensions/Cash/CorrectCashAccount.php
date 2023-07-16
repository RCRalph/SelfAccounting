<?php

namespace App\Rules\Extensions\Cash;

use Illuminate\Contracts\Validation\Rule;

class CorrectCashAccount implements Rule
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
        return $value == null || auth()->user()->accounts()
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
        return 'Cash account is invalid.';
    }
}
