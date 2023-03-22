<?php

namespace App\Rules\Extensions\Backup;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrAccount implements Rule
{
    private $data, $checkTransactions;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($data, $checkTransactions = false)
    {
        $this->data = $data;
        $this->checkTransactions = $checkTransactions;
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

        if ($value == 0) {
            return true;
        }
        else if (count($this->data) < $value) {
            return false;
        }

        if ($this->checkTransactions) {
            $field = is_string($this->checkTransactions) ?
                request("$prefix.$this->checkTransactions") :
                substr($attribute, 0, strpos($attribute, "."));

            if (!$this->data[$value - 1]["used_in_" . $field]) {
                return false;
            }
        }

        return $this->data[$value - 1]["currency"] == request("$prefix.currency");
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "This category / account doesn't exist.";
    }
}
