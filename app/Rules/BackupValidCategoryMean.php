<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BackupValidCategoryOrMean implements Rule
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

        $index = explode(".", $attribute)[1];
        $isCategory = strpos($attribute, "category") !== false;
        $selectedType = (
            $isCategory ?
            request("categories") : request("means")
        )[$value - 1];

        return $selectedType["currency_id"] == request("$this->type.$index.currency_id") &&
            $selectedType[$this->type . ($isCategory ? "_category" : "_mean")];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid category / mean.';
    }
}
