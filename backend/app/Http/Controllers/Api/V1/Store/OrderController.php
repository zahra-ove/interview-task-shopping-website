<?php

namespace App\Http\Controllers\Api\V1\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Order\OrderItemAddRequest;
use App\Http\Requests\Api\V1\Order\OrderItemRemoveRequest;
use App\Services\V1\OrderService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function addProduct(OrderItemAddRequest $request): JsonResponse
    {
        $result = $this->orderService->addProduct($request->toDto()->toArray());
        return response()->json($result, HttpResponse::HTTP_OK);
    }

    public function removeProduct(string $productId): JsonResponse
    {
        $result = $this->orderService->removeProduct($productId);
        return response()->json($result, HttpResponse::HTTP_OK);
    }

    public function increaseProductCount(OrderItemAddRequest $request): JsonResponse
    {
        $result = $this->orderService->changeProductCountBy($request->toDto()->toArray(), 'increase');
        return response()->json($result, HttpResponse::HTTP_OK);
    }

    public function decreaseProductCount(OrderItemRemoveRequest $request): JsonResponse
    {
        $result = $this->orderService->changeProductCountBy($request->toDto()->toArray(), 'decrease');
        return response()->json($result, HttpResponse::HTTP_OK);
    }

    public function pay(): JsonResponse
    {
        $result = $this->orderService->payment();
        return response()->json($result, HttpResponse::HTTP_OK);
    }

}
