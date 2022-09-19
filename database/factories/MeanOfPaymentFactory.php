<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MeanOfPayment;
use Carbon\Carbon;

class MeanOfPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeanOfPayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => 1,
            "currency_id" => 1,
            "name" => $this->faker->realText(rand(10, 32)),
            "income_mean" => $this->faker->boolean,
            "outcome_mean" => $this->faker->boolean,
            "count_to_summary" => $this->faker->boolean,
            "show_on_charts" => $this->faker->boolean,
            "first_entry_date" => Carbon::now()->subDays(30),
            "first_entry_amount" => $this->faker->randomFloat(2, 0, 100)
        ];
    }
}
