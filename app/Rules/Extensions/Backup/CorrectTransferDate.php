<?php

namespace App\Rules\Extensions\Backup;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CorrectTransferDate implements Rule
{
    private $accounts;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($accounts)
    {
        $this->accounts = $accounts;
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
        $sourceAccountID = request("$prefix.source_account_id");
        $targetAccountID = request("$prefix.target_account_id");

        if (count($this->accounts) < max($sourceAccountID, $targetAccountID)) return false;

        return max(
            strtotime($this->accounts[$sourceAccountID - 1]["start_date"]),
            strtotime($this->accounts[$targetAccountID - 1]["start_date"])
        ) <= strtotime($value);
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
