<?php

namespace App\Rules\Common;

use Illuminate\Contracts\Validation\Rule;

class SameLengthAs implements Rule
{
    private $secondArray;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($secondArray)
    {
        $this->secondArray = $secondArray;
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
        return count($value) == count(request($this->secondArray));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Array has different length than $this->secondArray.";
    }
}
