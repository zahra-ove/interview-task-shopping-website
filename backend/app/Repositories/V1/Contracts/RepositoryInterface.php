<?php

namespace App\Repositories\V1\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = [], array $parameters = [], ?string $orderBy = null): Collection;
    public function paginate(int $limit=20, array $columns = ['*'], array $relations = [], array $parameters = [], ?string $orderBy = null): LengthAwarePaginator;

    public function getBy($parameters, array $columns = ['*'], array $relations = []): Collection;
    public function pluck(array $fields = ['id']);
    public function find($id, array $columns = ['*'], array $relations = []);

    public function findBy($field, $value, array $columns = ['*'], array $relations = [], array $appends = []): ?Model;

    public function store(array $data): Model;

    public function update(int $id, array $data): bool;

    public function destroy(int $id): bool;

    public function count(): int;
}
