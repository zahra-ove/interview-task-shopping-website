<?php

namespace App\Http\Controllers\Api\V1\Store;

use App\Http\Controllers\Controller;
use app\Http\Resources\Api\V1\Product\ProductCollection;
use app\Http\Resources\Api\V1\Product\ProductResource;
use App\Repositories\V1\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository)
    {
    }

    public function index(): JsonResponse
    {
        $resource = request()->has('paginated')
            ? new ProductCollection($this->productRepository->paginate(request()->has('perpage') ? request()->input('perpage'): 10))
            : ProductResource::collection($this->productRepository->all());

        return response()->json($resource, HttpResponse::HTTP_OK);
    }

    public function show(string $id): JsonResponse
    {
        $resource = new ProductResource($this->productRepository->show($id));
        return response()->json($resource, HttpResponse::HTTP_OK);
    }
}
