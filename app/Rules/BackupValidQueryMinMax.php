<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BackupValidQueryMinMax implements Rule
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
        $index1 = explode(".", $attribute)[2];
        $index2 = explode(".", $attribute)[4];
        $min = request("bundleData.reports.$index1.queries.$index2.min_$this->type");
        $max = request("bundleData.reports.$index1.queries.$index2.min_$this->type");

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
