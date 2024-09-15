<?php

namespace App\Http\Requests\Api\V1\Order;

use App\DTO\V1\OrderDTO;
use Illuminate\Foundation\Http\FormRequest;

class OrderItemRemoveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => 'required|string|exists:products,_id',
            'count'      => 'nullable|numeric',
            'price'      => 'required|numeric'
        ];
    }

    public function toDto(): OrderDTO
    {
        return new OrderDTO(
            productId: $this->validated('product_id'),
            count: $this->validated('count'),
            price: $this->validated('price')
        );
    }
}
