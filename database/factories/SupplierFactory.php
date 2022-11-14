<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name_supplier' => $this->faker->name(),
            'address' => $this->faker->address(),
            'phone_supplier' => $this->faker->phoneNumber(),
            'status' => 1,
        ];
    }
}
