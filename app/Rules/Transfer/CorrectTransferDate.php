<?php

namespace App\Rules\Transfer;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CorrectTransferDate implements Rule
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
        if (!request()->has(["source.account_id", "target.account_id"])) return false;

        $maxDate = auth()->user()->accounts()
            ->select(DB::raw("MAX(start_date) AS max_date"))
            ->whereIn("id", [request("source.account_id"), request("target.account_id")])
            ->first();

        return $maxDate ? strtotime($maxDate->max_date) <= strtotime($value) : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Date must be after the starting date of both accounts.';
    }
}
