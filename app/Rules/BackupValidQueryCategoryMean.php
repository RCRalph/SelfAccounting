<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BackupValidQueryCategoryMean implements Rule
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
        if ($value == 0) {
            return true;
        }

        $index1 = explode(".", $attribute)[2];
        $index2 = explode(".", $attribute)[4];

        $selectedType = (
            strpos($attribute, "category") !== false ?
            request("categories") : request("means")
        )[$value - 1];

        return $selectedType["currency_id"] == request("bundleData.reports.$index1.$this->type.$index2.currency_id");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Invalid $this->type.";
    }
}
