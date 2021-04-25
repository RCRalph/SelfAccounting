<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BackupValidCategoryMean implements Rule
{
    private $type = "";
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        $this->type = $type;
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
        $index = explode(".", $attribute)[1];

        if ($checkingCategory) {
            $categories = collect(request("categories"));
            return $categories
                ->where("currency_id", request("$this->type.$index.currency_id"))
                ->where($this->type . "_category", true)
                ->count();
        }

        $means = collect(request("means"));
        return $means
            ->where("currency_id", request("$this->type.$index.currency_id"))
            ->where($this->type . "_mean", true)
            ->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
