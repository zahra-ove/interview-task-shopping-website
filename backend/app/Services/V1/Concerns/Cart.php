<?php

namespace App\Services\V1\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


trait Cart
{
    public string $key = '';
    public null|string $userId = null;
    private int $ttl = 60;

    public function setCartKey(): void
    {
        $this->userId = (string)auth()->payload()->get('sub');
        $this->key = "user:$this->userId:cart";
    }

    private function cartExists(): bool
    {
        return cache()->has($this->key);
    }

    private function createCart(array $cart): bool
    {
        $initCart = [
            'total_price' => 0,
            'total_count' => 0,
            'user_id'     => $this->userId,
            'order_items' => []     // [['product_id' => 1, 'count'=> 3, 'price' => 1000], ['product_id' => 2, 'count'=> 53, 'price' => 8000], ['product_id' => 3, 'count'=> 7, 'price' => 9800]]
        ];

        $cart = array_merge($initCart, $cart);

        Log::info("key to cache:", [$this->key]);
        return cache()->put($this->key, json_encode($cart), $this->ttl);
    }

    private function getCart(): null|array
    {
        $cart = cache()->get($this->key);
        return json_decode($cart, true);
    }

    private function setCart(array $data): bool
    {
        return cache()->put($this->key, json_encode($data), $this->ttl);
    }

    private function removeCart(): bool
    {
        return cache()->forget($this->key);
    }

    public function ItemExistsInCart(string $productId): bool
    {
        $order_items = $this->getCart()['order_items'];
        Log::info("order_items:", ["items"=>$order_items, "cart"=>$this->getCart()]);
        if(empty($order_items)) {
            return false;
        }

        $productIds = array_column($order_items, 'product_id');
        return in_array($productId, $productIds);
    }

    private function getItemIndexInCart(string $productId): null|int
    {
        if( ! $this->ItemExistsInCart($productId)) {
            return null;
        }

        $order_items = $this->getCart()['order_items'];
        $productIds = array_column($order_items, 'product_id');
        return array_search($productId, $productIds);
    }
}
