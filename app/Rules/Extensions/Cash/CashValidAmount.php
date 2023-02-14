<?php

namespace App\Rules\Extensions\Cash;

use Illuminate\Contracts\Validation\Rule;

class CashValidAmount implements Rule
{
    private $addition, $directory;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($addition = true, $directory = "cash")
    {
        $this->addition = $addition;
        $this->directory = $directory;
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
        $index = explode(".", $attribute)[substr_count($attribute, ".") - 1];
        $id = request("$this->directory.$index.id");

        $ownedCash = auth()->user()->cash()->find($id);

        if ($ownedCash) {
            return $this->addition ?
                $ownedCash->pivot->amount + $value <= PHP_INT_MAX :
                $ownedCash->pivot->amount >= $value;
        }

        return $this->addition ? $value <= PHP_INT_MAX : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Insufficient amount of cash.";
    }
}
