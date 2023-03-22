<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
            "used_in_income" => $this->faker->boolean,
            "used_in_expenses" => $this->faker->boolean,
            "count_to_summary" => $this->faker->boolean,
            "show_on_charts" => $this->faker->boolean
        ];
    }
}
