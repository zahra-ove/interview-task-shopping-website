<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'      => $this->faker->word(),
            'price'     => $this->faker->randomFloat(0, 10, 1000000000),
            'inventory' => $this->generateInventory()
        ];
    }

    private function generateInventory(): array
    {
        // Fetch some random inventory IDs using MongoDB's aggregation pipeline
        $inventoryIds = Inventory::raw(function ($collection) {
            return $collection->aggregate([
                ['$sample' => ['size' => 5]], // Adjust 'size' to the number of items you need
                ['$project' => ['_id' => 1]],
            ]);
        })->pluck('_id')->toArray();

        $inventoryItems = [];

        foreach ($inventoryIds as $inventoryId) {
            $inventoryItems[] = [
                'inventory_id' => $inventoryId,
                'count' => $this->faker->numberBetween(1, 100),
            ];
        }

        return $inventoryItems;
    }
}
