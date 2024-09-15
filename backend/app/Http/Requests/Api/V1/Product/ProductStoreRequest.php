<?php

namespace app\Http\Requests\Api\V1\Product;

use app\DTO\V1\ProductDTO;
use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                     => 'required|string|max:500',
            'price'                    => 'required|numeric',
            'inventory'                => 'nullable|array',
            'inventory.*'              => 'required_with:inventory|array',
            'inventory.*.inventory_id' => 'required|string',
            'inventory.*.count'        => 'required|numeric',
        ];
    }


    public function toDto(): ProductDTO
    {
        return new ProductDTO(
            name: $this->validated('name'),
            price: $this->validated('price'),
            inventoriesData: $this->validated('inventory')
        );
    }
}
