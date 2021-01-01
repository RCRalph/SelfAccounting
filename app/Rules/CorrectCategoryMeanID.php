<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\MeanOfPayment;
use App\Category;

class CorrectCategoryMeanID implements Rule
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

        $queryArray = [
            ["id", "=", $value],
            ["user_id", "=", auth()->user()->id]
        ];

        if ($this->type == "category") {
            return Category::where($queryArray)->exists();
        }
        else {
            return MeanOfPayment::where($queryArray)->exists();
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The given ID is invalid.';
    }
}
