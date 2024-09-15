<?php

namespace app\Http\Resources\Api\V1\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type'          => 'products',
            'id'            => $this->_id,
            'attributes'      => [
                'name'        => $this->name,
                'price'       => $this->price,
                'total_stock' => $this->total_available,
                'inventory'   => $this->when(
                    $request->routeIs('v1:admin.products.show'),
                    $this->inventory),
            ],
            'relationships' => [],
            'includes'      => [],
            'links'         => [
                'self' => route('v1:admin.products.show', ['id' => $this->_id])
            ]
        ];
    }
}
