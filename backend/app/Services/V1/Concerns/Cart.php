<?php

namespace App\Services\V1\Concerns;

use Illuminate\Support\Facades\Cache;


trait Cart
{
    public string $key = '';
    public null|string $userId = null;
    private int $ttl = 604800; // one week

    public function setCartKey(): void
    {
        $this->userId = (string)auth()->user()->getKey();
        $this->key = "user:$this->userId:cart";
    }

    private function cartExists(): bool
    {
        return Cache::tags('cart')->has($this->key);
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

        return Cache::tags('cart')->put($this->key, json_encode($cart), $this->ttl);
    }

    private function getCart(): null|array
    {
        $cart = Cache::tags('cart')->get($this->key);
        return json_decode($cart, true);
    }

    private function setCart(array $data): bool
    {
        return Cache::tags('cart')->put($this->key, json_encode($data), $this->ttl);
    }

    private function removeCart(): bool
    {
        return Cache::tags('cart')->forget($this->key);
    }

    public function ItemExistsInCart(string $productId): bool
    {
        $order_items = $this->getCart()['order_items'];
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
