<?php

namespace app\DTO\V1;

class ProductDTO
{
    public function __construct(private string $name, private int $price, private null|array $inventoriesData)
    {
    }

    public function toArray(): array
    {
        return [
            'name'      => $this->name,
            'price'     => $this->price,
            'inventory' => $this->prepare_inventory($this->inventoriesData),
        ];
    }

    private function prepare_inventory(null|array $inventories): null|array
    {
        $inventoriesList = [];

        if( ! $inventories) {
            return null;
        }

        foreach($inventories as $inventory) {
            $inventoriesList[] =[
                'inventory_id' => $inventory['inventory_id'],
                'count'        =>  $inventory['count']
            ];
        }

        return $inventoriesList;
    }
}
