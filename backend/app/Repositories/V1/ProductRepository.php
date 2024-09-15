<?php

namespace app\Repositories\V1;

use app\DTO\V1\ProductDTO;
use App\Models\Product;
use App\Repositories\V1\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;


class ProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::all();
    }

    public function paginate(int $perPage=10): LengthAwarePaginator
    {
        return Product::query()->paginate($perPage);
    }

    public function store(array $productData): null|Product
    {
        return Product::create($productData);
    }

    public function show(string $id): null|Product
    {
        return Product::findOrFail($id);
    }

    public function update(string $id, array $productData): int
    {
        $product = Product::findOrFail($id);
        return $product->update($productData);
    }

    public function destroy(string $id): int
    {
        return Product::where('_id', $id)->delete();
    }
}
