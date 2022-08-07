<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Currency;

class CurrencySeeder extends Seeder
{
    private $currencies = [
        "USD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.25, 0.1, 0.05, 0.01],
        "EUR" => [500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
        "JPY" => [10000, 5000, 2000, 1000, 500, 100, 50, 10, 5, 1],
        "GBP" => [50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
        "AUD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05],
        "CAD" => [100, 50, 20, 10, 5, 2, 1, 0.5, 0.25, 0.1, 0.05, 0.01],
        "CHF" => [1000, 500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05],
        "PLN" => [500, 200, 100, 50, 20, 10, 5, 2, 1, 0.5, 0.2, 0.1, 0.05, 0.02, 0.01],
        "INR" => [2000, 500, 200, 100, 50, 20, 10, 5, 2, 1]
    ];

    /**
     * Run the database seeds.w
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->currencies as $ISO => $cash) {
            $currency = Currency::firstOrCreate(compact("ISO"));
            sort($cash);

            foreach ($cash as $value) {
                if ($currency->cash()->where("value", $value)->doesntExist()) {
                    $currency->cash()->create(compact("value"));
                }
            }

            $currency->cash()->whereNotIn("value", $cash)->delete();
        }
    }
}
