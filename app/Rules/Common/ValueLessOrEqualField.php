<?php

namespace App\Rules\Common;

use Illuminate\Contracts\Validation\Rule;

class ValueLessOrEqualField implements Rule
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
        $prefix = substr($attribute, 0, strrpos($attribute, "."));
        if ($prefix) {
            $this->otherField = "$prefix.$this->otherField";
        }

        if ($value == null || request($this->otherField) == null) {
            return true;
        }

        return $value <= request($this->otherField);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute has to be less or equal $this->otherField";
    }
}
