<?php

namespace App\Rules\Extensions\Reports;

use Illuminate\Contracts\Validation\Rule;

class BelongsToReport implements Rule
{
    private $report, $data;

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
        if (!$value) return true;

        if (str_contains($attribute, "queries")) {
            $this->data = $this->report->queries();
        } else if (str_contains($attribute, "additionalEntries")) {
            $this->data = $this->report->additionalEntries();
        } else {
            abort(500, "Invalid data type for attribute check");
        }

        return $this->data->where("id", $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "This attribute doesn't belong to this report";
    }
}
