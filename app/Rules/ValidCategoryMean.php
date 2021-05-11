<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCategoryMean implements Rule
{
    private $viewType, $checkForIncomeOutcome, $nestedArrayName;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        $viewType,
        $checkForIncomeOutcome = true,
        $nestedArrayName = "data"
    )
    {
        $this->viewType = $viewType;
        $this->checkForIncomeOutcome = $checkForIncomeOutcome;
        $this->nestedArrayName = $nestedArrayName;
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

        $retVal;
        $index = explode(".", $attribute)[1];
        if (strpos($attribute, "category") !== false) {
            $retVal = auth()->user()->categories
                ->where("id", $value)
                ->where("currency_id", request("$this->nestedArrayName.$index.currency_id"));
            if ($this->checkForIncomeOutcome) {
                $retVal = $categories->where($this->viewType . "_category", true);
            }
        }
        else {
            $retVal = auth()->user()->meansOfPayment
                ->where("id", $value)
                ->where("currency_id", request("$this->nestedArrayName.$index.currency_id"));
            if ($this->checkForIncomeOutcome) {
                $retVal = $retVal->where($this->viewType . "_mean", true);
            }
        }

        return $retVal->count();
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
