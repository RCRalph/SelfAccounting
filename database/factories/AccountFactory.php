<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Account;
use Carbon\Carbon;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

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
            "used_in_income" => $this->faker->boolean,
            "used_in_expences" => $this->faker->boolean,
            "count_to_summary" => $this->faker->boolean,
            "show_on_charts" => $this->faker->boolean,
            "start_date" => Carbon::now()->subDays(30),
            "start_balance" => $this->faker->randomFloat(2, 0, 100)
        ];
    }
}
