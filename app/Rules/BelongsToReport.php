<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BelongsToReport implements Rule
{
    private $report;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($report)
    {
        $this->report = $report;
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
        if ($value === null) {
            return true;
        }

        if (strpos($attribute, "queries") === 0) {
            return in_array($value,
                $this->report->queries
                    ->map(fn ($item) => $item->id)
                    ->toArray()
            );
        }

        return in_array($value,
            $this->report->additionalEntries
                ->map(fn ($item) => $item->id)
                ->toArray()
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "This doesn't belong to this report";
    }
}
