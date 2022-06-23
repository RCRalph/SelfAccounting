<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\MeanOfPayment;

class CorrectDateIOUpdate implements Rule
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
        $id = request("mean_id");

        if ($id == 0) {
            return true;
        }

        $firstEntryDate = auth()->user()->meansOfPayment()
            ->where("id", $id)->get()
            ->firstOrFail()->first_entry_date;

        return strtotime($firstEntryDate) <= strtotime($value);
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
