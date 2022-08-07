<?php

namespace App\Rules\Extensions\Cash;

use Illuminate\Contracts\Validation\Rule;

class CashValidAmount implements Rule
{
    private $type, $directory;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($type, $directory = "cash")
    {
        $this->type = $type;
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
        if ($this->type == "income") {
            return true;
        }

        $index = explode(".", $attribute)[1];
        $id = request("$this->directory.$index.id");

        $ownedCash = auth()->user()->cash()->find($id);

        if ($ownedCash) {
            return $ownedCash->pivot->amount >= $value;
        }

        return false;
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
