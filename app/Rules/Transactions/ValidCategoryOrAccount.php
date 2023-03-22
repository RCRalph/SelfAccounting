<?php

namespace App\Rules\Transactions;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryOrAccount implements Rule
{
    private $currency_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Currency $currency = null)
    {
        $this->currency_id = $currency === null ?
            request("currency_id") : $this->currency->id;
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

        $data = null;
        if (str_starts_with($attribute, "account")) {
            $data = auth()->user()->accounts();
        } else if (str_starts_with($attribute, "category")) {
            $data = auth()->user()->categories();
        } else {
            abort(500, "Invalid data type");
        }

        return $data
            ->where("id", $value)
            ->where("currency_id", $this->currency_id)
            ->where("used_in_" . request()->type, true)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $type = substr(request()->type, 0, -1);

        return "This $type doesn't exist.";
    }
}
