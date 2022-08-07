<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CorrectFirstEntryDate implements Rule
{
    private $mean;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($mean)
    {
        $this->mean = $mean;
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
        $minDate = auth()->user()->income()
            ->select("date")
            ->where("mean_id", $this->mean->id)
            ->union(auth()->user()->outcome()
                ->select("date")
                ->where("mean_id", $this->mean->id)
            )
            ->orderBy("date")
            ->first();

        return $minDate == null ? true : strtotime($value) <= strtotime($minDate->date);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The first entry starting date must be before the first income / outcome entry.';
    }
}
