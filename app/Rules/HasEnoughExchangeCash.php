<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use App\Currency;
use App\Cash;

class HasEnoughExchangeCash implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getCashMeans()
    {
        $cashMeansList = auth()->user()->cashMeans
            ->map(fn ($item) => $item->only("currency_id", "id"))
            ->groupBy("currency_id");

        $cashMeans = [];
        foreach ($cashMeansList as $currency => $value) {
            $cashMeans[$currency] = $value[0]["id"];
        }

        foreach (Currency::all() as $currency) {
            if (!isset($cashMeans[$currency->id])) {
                $cashMeans[$currency->id] = null;
            }
        }

        return $cashMeans;
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
        $index = explode(".", $attribute)[1];
        $id = request("cash.$index.id");

        $outcomeCurrency = request("outcome.currency_id");
        $outcomeMean = request("outcome.mean_id");
        $cashMeans = $this->getCashMeans();

        if ($cashMeans[$outcomeCurrency] == $outcomeMean && Cash::find($id)->currency_id == $outcomeCurrency) {
            $cash = auth()->user()->cash()->find($id);
            return $cash ? $cash->pivot->amount >= $value : false;
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
        return 'Invalid amount of cash.';
    }
}
