<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectDateMeans implements Rule
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
        $indeces = explode(".", $attribute);
        $date = request()->input("data.$indeces[1].$indeces[2].first_entry_date");
        $id = request()->input("data.$indeces[1].$indeces[2].id");

        if ($id == 0) {
            return true;
        }

        $earliestIncomeDate = auth()->user()->income
            ->concat(auth()->user()->outcome)
            ->where("mean_id", $id)
            ->sortBy("date")->last();

        return $earliestIncomeDate == null ? true : strtotime($date) <= strtotime($earliestIncomeDate->date);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The first entry starting date must be before the first income / outcome entry.';
    }
}
