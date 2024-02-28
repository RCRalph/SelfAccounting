<?php

namespace App\Rules\Extensions\Cash;

use Illuminate\Contracts\Validation\Rule;

class ValidCashAmount implements Rule
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
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $index = explode(".", $attribute)[substr_count($attribute, ".") - 1];
        $id = request("$this->directory.$index.id");

        $ownedCash = auth()->user()->cash()->find($id)->pivot->amount ?? 0;

        return match ($this->type) {
            "income" => 0 <= $ownedCash + $value && $ownedCash + $value <= PHP_INT_MAX,
            "expenses" => 0 <= $ownedCash - $value && $ownedCash - $value <= PHP_INT_MAX,
            default => false,
        };
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
