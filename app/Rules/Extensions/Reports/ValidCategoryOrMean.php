<?php

namespace App\Rules\Extensions\Reports;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrMean implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        $prefix = substr($attribute, 0, strrpos($attribute, "."));

        $query = (str_contains($attribute, "category") ? auth()->user()->categories() : auth()->user()->meansOfPayment())
            ->where("currency_id", request("$prefix.currency_id"))
            ->where("id", $value);

        if (request("$prefix.query_data")) {
            $query = $query->where(request("$prefix.query_data") . "_" . (str_contains($attribute, "category") ? "category" : "mean"), true);
        }

        return $query->exists();
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
