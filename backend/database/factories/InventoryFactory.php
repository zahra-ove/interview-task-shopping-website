<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    public function definition(): array
    {
        $inventoryNumber = $this->faker->unique()->numberBetween(1, 10);

        return [
            'name'        => 'Inventory' . $inventoryNumber,
            'description' => 'Inventory number' . $inventoryNumber,
        ];
    }
}
