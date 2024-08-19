<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DistinctMultipleFields implements Rule
{
    private array $fields;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    private function getCombination(string $field_prefix): string
    {
        $field_values = [];

        foreach ($this->fields as $field) {
            $field_values[] = request("$field_prefix.$field");
        }

        return implode("\0", $field_values);
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
        $field_prefix = substr($attribute, 0, strrpos($attribute, "."));
        $index = substr($field_prefix, strpos($field_prefix, ".") + 1);
        $prefix = substr($field_prefix, 0, strrpos($field_prefix, "."));

        $field_combination = $this->getCombination($field_prefix);
        for ($i = 0; $i < $index; $i++) {
            if ($this->getCombination("$prefix.$i") === $field_combination) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Combination of distinct fields does not match the requested values.";
    }
}
