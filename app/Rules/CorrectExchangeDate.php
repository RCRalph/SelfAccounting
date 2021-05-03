<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\MeanOfPayment;

class CorrectExchangeDate implements Rule
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
        $incomeMeanID = request("income.mean_id");
        $outcomeMeanID = request("outcome.mean_id");

        $incomeDate = !$incomeMeanID ? 0 : MeanOfPayment::findOrFail($incomeMeanID)->first_entry_date;
        $outcomeDate = !$outcomeMeanID ? 0 : MeanOfPayment::findOrFail($outcomeMeanID)->first_entry_date;

        return max(strtotime($incomeDate), strtotime($outcomeDate)) <= strtotime($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Entry too early for given means of payment.';
    }
}
