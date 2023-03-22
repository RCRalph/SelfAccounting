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
        $source_account_id = request("$prefix.source_account_id");
        $target_account_id = request("$prefix.target_account_id");

        if (count($this->accounts) < max($source_account_id, $target_account_id)) {
            return false;
        }

        $maxAccountDate = max(
            strtotime($this->accounts[$source_account_id - 1]["start_date"]),
            strtotime($this->accounts[$target_account_id - 1]["start_date"])
        );

        return $maxAccountDate <= strtotime($value);
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
