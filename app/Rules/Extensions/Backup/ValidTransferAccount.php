<?php

namespace App\Rules\Extensions\Backup;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ValidTransferAccount implements Rule
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
        return count($this->accounts) >= $value && $value > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "This account doesn't exist.";
    }
}
