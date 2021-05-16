<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidQueryMinMax implements Rule
{
    private $type;

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
        $min = request("queries.$index.min_$this->type");
        $max = request("queries.$index.max_$this->type");

        if ($min == null || $max == null) {
            return true;
        }

        return $min <= $max;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Minimal $this->type is greater than maximal $this->type";
    }
}
