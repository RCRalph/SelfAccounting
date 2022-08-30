<?php

namespace App\Rules\Extensions\Backup;

use Illuminate\Contracts\Validation\Rule;

class CorrectDateIO implements Rule
{
    private $means;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($means)
    {
       $this->means = $means;
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
        $id = request("$prefix.mean_id");

        if ($id == 0) {
            return true;
        }
        else if (count($this->means) < $id) {
            return false;
        }

        return strtotime($this->means[$id - 1]["first_entry_date"]) <= strtotime($value);
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
