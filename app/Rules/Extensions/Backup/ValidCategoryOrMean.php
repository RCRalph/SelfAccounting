<?php

namespace App\Rules\Extensions\Backup;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrMean implements Rule
{
    private $data, $checkIO;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($data, $checkIO = false)
    {
        $this->data = $data;
        $this->checkIO = $checkIO;
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

        if ($this->checkIO) {
            $field = is_string($this->checkIO) ?
                request("$prefix.$this->checkIO") :
                substr($attribute, 0, strpos($attribute, "."));

            if (!$this->data[$value - 1][$field . "_" . (str_contains($attribute, "category") ? "category" : "mean")]) {
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
        return "This category / mean of payment doesn't exist.";
    }
}
