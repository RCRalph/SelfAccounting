<?php

namespace App\Rules\Exchange;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrAccountExchange implements Rule
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
        if ($value == null) {
            return true;
        }

        return (str_contains($attribute, "category") ? auth()->user()->categories() : auth()->user()->accounts())
            ->where("id", $value)
            ->where("currency_id", request("$this->key.currency_id"))
            ->where(($this->key == "from" ? "outcome" : "income") . "_" . (str_contains($attribute, "category") ? "category" : "account"), true)
            ->count();
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
