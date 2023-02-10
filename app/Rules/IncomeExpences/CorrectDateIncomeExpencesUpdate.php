<?php

namespace App\Rules\IncomeExpences;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Account;

class CorrectDateIncomeExpencesUpdate implements Rule
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
        $id = request("account_id");

        if ($id == 0) {
            return true;
        }

        $account = auth()->user()->accounts()->where("id", $id)->first();
        if (!$account) {
            return false;
        }

        return strtotime($account->start_date) <= strtotime($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The date must be before the first entry starting date.';
    }
}
