<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
use App\Models\Income;
use App\Models\User;

class IncomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Income::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $category = User::find(1)
            ->categories
            ->where("currency_id", 1)
            ->where("used_in_income", true)
            ->random();

        $account = User::find(1)
            ->accounts
            ->where("currency_id", 1)
            ->where("used_in_income", true)
            ->random();

        return [
            "user_id" => 1,
            "date" => Carbon::now()->subDays(rand(0, 30)),
            "title" => $this->faker->name,
            "amount" => $this->faker->randomFloat(3, 1, 100),
            "price" => $this->faker->randomFloat(2, 1, 100),
            "currency_id" => 1,
            "category_id" => $category->id,
            "account_id" => $account->id
        ];
    }
}
