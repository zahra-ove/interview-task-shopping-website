<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\Product\ProductStoreRequest;
use App\Http\Requests\Api\V1\Product\ProductUpdateRequest;
use app\Http\Resources\Api\V1\Product\ProductCollection;
use app\Http\Resources\Api\V1\Product\ProductResource;
use App\Repositories\V1\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ProductController extends Controller
{
    public function __construct(private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function index(): JsonResponse
    {
        $resource = request()->has('paginated')
            ? new ProductCollection($this->productRepository->paginate())
            : ProductResource::collection($this->productRepository->all());

        return response()->json($resource, HttpResponse::HTTP_OK);
    }

    public function show(string $id): JsonResponse
    {
        $resource = new ProductResource($this->productRepository->find($id));
        return response()->json($resource, HttpResponse::HTTP_OK);
    }

    public function store(ProductStoreRequest $request): JsonResponse
    {
        $post = $this->productRepository->store($request->toDto()->toArray());
        return response()->json($post, HttpResponse::HTTP_CREATED);
    }

    public function update(string $id, ProductUpdateRequest $request): JsonResponse
    {
        $post = $this->productRepository->update($id, $request->toDto()->toArray());
        return response()->json($post, HttpResponse::HTTP_OK);
    }

    public function delete(string $id): JsonResponse
    {
        $result = $this->productRepository->destroy($id);
        return response()->json(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
