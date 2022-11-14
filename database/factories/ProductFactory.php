<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'Re_order_limit' => $this->faker->randomNumber(),
            'maximum_product' => $this->faker->randomNumber(),
            'category_id' => 1,
            'sub_category_id' => 2,
            'company_id' => 1,
            'main_measurement_unit_id' => 1,
            'sub_measurement_unit_id' => 2,
            'count_unit'=>$this->faker->numberBetween(1,24),
        ];
    }
}
