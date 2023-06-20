<?php

namespace App\Rules\Accounts;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CorrectStartDate implements Rule
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
        $selectQuery = DB::raw("MIN(date) AS min_date");

        $income = auth()->user()->income()
            ->select($selectQuery)
            ->where("account_id", $this->account->id);

        $expenses = auth()->user()->expenses()
            ->select($selectQuery)
            ->where("account_id", $this->account->id);

        $minDate = $income->union($expenses)
            ->orderBy("min_date", "ASC")
            ->first()
            ->min_date;

        return $minDate == null ? true : strtotime($value) <= strtotime($minDate);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The start date must be before the first transaction entry.';
    }
}
