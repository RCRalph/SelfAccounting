<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Expence;
use App\Models\User;
use Carbon\Carbon;

class ExpenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expence::class;

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
            ->where("used_in_expences", true)
            ->random();

        $account = User::find(1)
            ->accounts
            ->where("currency_id", 1)
            ->where("used_in_expences", true)
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
