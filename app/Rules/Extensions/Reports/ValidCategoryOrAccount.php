<?php

namespace App\Rules\Extensions\Reports;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrAccount implements Rule
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

        $query = (str_contains($attribute, "category") ? auth()->user()->categories() : auth()->user()->accounts())
            ->where("currency_id", request("$prefix.currency_id"))
            ->where("id", $value);

        if (request("$prefix.query_data")) {
            $query = $query->where("used_in_" . request("$prefix.query_data"), true);
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
        return "This category / account doesn't exist.";
    }
}
