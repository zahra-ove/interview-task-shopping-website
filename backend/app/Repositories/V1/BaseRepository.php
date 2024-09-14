<?php

namespace App\Repositories\V1;

use App\Repositories\V1\contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class BaseRepository implements RepositoryInterface
{

    public function __construct(private Model $model)
    {
    }

    public function all(array $columns = ['*'], array $relations = [], array $parameters = [], ?string $orderBy = null): Collection
    {
        return $this->model
            ->with($relations)
            ->select($columns)
            ->get();
    }

    public function paginate(int $limit=20, array $columns = ['*'], array $relations = [], array $parameters = [], ?string $orderBy = null): LengthAwarePaginator
    {
        return $this->model
            ->with($relations)
            ->select($columns)
            ->paginate($limit);
    }

    protected function applyFilters(array $filters = []): void
    {
        // Should be implemented in specific repositories.
    }

    public function getBy($parameters, array $columns = ['*'], array $relations = []): Collection
    {
        foreach ($parameters as $field => $value) {
            $this->model->where($field, $value);
        }

        return $this->model
            ->with($relations)
            ->select($columns)
            ->get();
    }

    public function pluck(array $fields = ['id'])
    {
        return $this->model
            ->pluck(...$fields)
            ->all();
    }

    public function find($id, array $columns = ['*'], array $relations = [])
    {
        return $this->model
            ->with($relations)
            ->select($columns)
            ->findOrFail($id);
    }

    public function findBy($field, $value, array $columns = ['*'], array $relations = [], array $appends = []): ?Model
    {
        return $this->model
            ->with($relations)
            ->select($columns)
            ->where($field, $value)
            ->first();
    }

    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function destroy(int $id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    public function count(): int
    {
        return $this->model->count();
    }
}
