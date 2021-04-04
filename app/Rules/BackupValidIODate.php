<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BackupValidIODate implements Rule
{
    private $type = "";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        $index = explode(".", $attribute)[1];
        $meanId = request("$this->type.$index.mean_id") - 1;

        if ($meanId == -1) {
            return true;
        }

        if (!request("means.$meanId")) {
            return false;
        }

        $dateToCheck = request("means.$meanId.first_entry_date");
        return strtotime($dateToCheck) <= strtotime($value);
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
