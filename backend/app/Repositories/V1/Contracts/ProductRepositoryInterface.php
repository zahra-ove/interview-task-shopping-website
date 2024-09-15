<?php

namespace app\Repositories\V1\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function all(): Collection;
    public function paginate(): LengthAwarePaginator;

    public function store(array $productData): null|Product; //@TODO: null ro check kon

    public function show(string $id): null|Product;

    public function update(string $id, array $productData): int;

    public function destroy(string $id): int;
}
