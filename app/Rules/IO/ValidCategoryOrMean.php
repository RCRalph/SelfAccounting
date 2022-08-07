<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrMean implements Rule
{
    private $type, $currency;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type, $currency)
    {
        $this->type = $type;
        $this->currency = $currency;
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
        if ($value == null) {
            return true;
        }

        $index = explode(".", $attribute)[1];

        return (str_contains($attribute, "category") ? auth()->user()->categories() : auth()->user()->meansOfPayment())
            ->where("currency_id", $this->currency->id)
            ->where("id", $value)
            ->count();
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
