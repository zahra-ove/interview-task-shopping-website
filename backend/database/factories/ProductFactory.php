<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(0, 10, 1000000000),
            'inventory_id' => 1  //@TODO: after defining inventory model, fix it
        ];
    }
}
