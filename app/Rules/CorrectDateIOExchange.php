<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectDateIOExchange implements Rule
{
    private $key;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($key)
    {
        $this->key = $key;
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
        $id = request("$this->key.mean_id");

        if ($id == 0) {
            return true;
        }

        $mean = auth()->user()->meansOfPayment()->where("id", $id)->first();
        if (!$mean) {
            return false;
        }

        return strtotime($mean->first_entry_date) <= strtotime($value);
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
