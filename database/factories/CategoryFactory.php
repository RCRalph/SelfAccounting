<?php

namespace Database\Factories;

use App\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

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
            "income_category" => $this->faker->boolean,
            "outcome_category" => $this->faker->boolean,
            "count_to_summary" => $this->faker->boolean,
            "show_on_charts" => $this->faker->boolean
        ];
    }
}
