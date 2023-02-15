<?php

namespace App\Rules\Extensions\Cash;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Cash;

class CorrectTransferCashCurrency implements Rule
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
        $type = explode(".", $attribute, 2)[0];
        $account = auth()->user()->accounts()
            ->firstWhere("id", request("$type.account_id"));

        return $account ? Cash::find($value)->currency_id == $account->currency_id : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Cash ID doesn't belong to account's currency.";
    }
}
