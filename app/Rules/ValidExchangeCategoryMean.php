<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidExchangeCategoryMean implements Rule
{
    private $viewType = "";
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($viewType)
    {
        $this->viewType = $viewType;
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

        $checkingCategory = strpos($attribute, "category") !== false;

        if ($checkingCategory) {
            return auth()->user()->categories
                ->where("id", $value)
                ->where("currency_id", request("$this->viewType.currency_id"))
                ->where($this->viewType . "_category", true)
                ->count();
        }

        return auth()->user()->meansOfPayment
            ->where("id", $value)
            ->where("currency_id", request("$this->viewType.currency_id"))
            ->where($this->viewType . "_mean", true)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid category / mean of payment';
    }
}
