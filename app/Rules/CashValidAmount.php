<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CashValidAmount implements Rule
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
        if (request()->type == "income") {
            return true;
        }

        $index = explode(".", $attribute)[1];
        $id = request("cash.$index.id");

        $ownedCash = auth()->user()->cash()->find($id);
        if ($ownedCash) {
            return $ownedCash->pivot->amount >= $value;
        }

        return false;
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
