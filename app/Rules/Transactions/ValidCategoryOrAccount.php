<?php

namespace App\Rules\Transactions;

use Illuminate\Contracts\Validation\Rule;

use App\Models\Currency;

class ValidCategoryOrAccount implements Rule
{
    private $currency_id, $data;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Currency $currency = null)
    {
        $this->currency_id = $currency === null ?
            request("currency_id") : $currency->id;
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
        if ($value == null) return true;

        if (str_contains($attribute, "account")) {
            $this->data = auth()->user()->accounts();
        } else if (str_contains($attribute, "category")) {
            $this->data = auth()->user()->categories();
        } else {
            abort(500, "Invalid data type for transactions");
        }

        return $this->data
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
