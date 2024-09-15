<?php

namespace App\DTO\V1;

class OrderDTO
{
    public function __construct(private string $productId, private int $count, private int $price)
    {
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'count'      => $this->count ?? 1,
            'price'      => $this->price,
        ];
    }
}
