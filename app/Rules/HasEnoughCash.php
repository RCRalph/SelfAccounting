<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class HasEnoughCash implements Rule
{
    private $type;
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
        if ($this->type == "income") {
            return true;
        }

        $index = explode(".", $attribute)[1];
        $id = request("cash.$index.id");

        $cash = auth()->user()->cash()->find($id);
        return $cash ? $cash->pivot->amount >= $value : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Not enough cash";
    }
}
