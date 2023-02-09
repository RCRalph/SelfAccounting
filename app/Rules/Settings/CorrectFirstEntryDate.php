<?php

namespace App\Rules\Settings;

use Illuminate\Contracts\Validation\Rule;

class CorrectFirstEntryDate implements Rule
{
    private $account;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($account)
    {
        $this->account = $account;
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
        $minDate = auth()->user()->income()
            ->select("date")
            ->where("account_id", $this->account->id)
            ->union(auth()->user()->outcome()
                ->select("date")
                ->where("account_id", $this->account->id)
            )
            ->orderBy("date")
            ->first();

        return $minDate == null ? true : strtotime($value) <= strtotime($minDate->date);
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
