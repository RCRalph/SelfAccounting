<?php

namespace Database\Factories;

use App\Outcome;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class OutcomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Outcome::class;

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
            ->where("outcome_category", true)
            ->random();

        $mean = User::find(1)
            ->meansOfPayment
            ->where("currency_id", 1)
            ->where("outcome_mean", true)
            ->random();

        return [
            "user_id" => 1,
            "date" => Carbon::now()->subDays(rand(0, 30)),
            "title" => $this->faker->name,
            "amount" => $this->faker->randomFloat(3, 1, 100),
            "price" => $this->faker->randomFloat(2, 1, 100),
            "currency_id" => 1,
            "category_id" => $category->id,
            "mean_id" => $mean->id
        ];
    }
}
