<?php

namespace App\Rules\Transactions;

use Illuminate\Contracts\Validation\Rule;

class CorrectTransactionDate implements Rule
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
        $prefix = substr($attribute, 0, strrpos($attribute, "."));
        $id = request("$prefix.account_id");

        if (!$id) return true;

        $account = auth()->user()->accounts()
            ->select("start_date")
            ->where("id", $id)
            ->first();

        return $account && strtotime($account->start_date) <= strtotime($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The date must be before the account start date.';
    }
}
