<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateBeforeOrEqualField implements Rule
{
    private $otherField;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($otherField)
    {
        $this->otherField = $otherField;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value == null || request($this->otherField) == null) {
            return true;
        }

        return strtotime($value) <= strtotime(request($this->otherField));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute has to be before $this->otherField";
    }
}
