<?php

namespace App\Services\V1;

use App\Repositories\V1\Contracts\OrderRepositoryInterface;
use App\Repositories\V1\Contracts\ProductRepositoryInterface;
use App\Services\V1\Concerns\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;


class OrderService
{
    use Cart;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductRepositoryInterface $productRepository
    )
    {
        $this->setCartKey();
    }

    public function addProduct(array $data): bool
    {
        if($this->cartExists()) {
            Log::info("card exists");
            if($this->ItemExistsInCart($data['product_id'])){
                Log::info("item already exist", ["item_id"=>$data['product_id']]);
                return $this->changeProductCountBy($data, 'increase');
            }
        }

        $this->createCart([]);
        return $this->addNewProduct($data);
    }

    private function addNewProduct(array $data): bool
    {
        // new data
        $productId = $data['product_id'];
        $count = (int)$data['count'];
        $price = (int)$data['price'];
        $newAddedAmount = ($count * $price);


        // old data (existing data in the cart)
        $oldCart = $this->getCart();
        $oldTotalPrice = (int)$oldCart['total_price'];
        $oldTotalCount = (int)$oldCart['total_count'];
        $oldOrderItems = $oldCart['order_items'];

        $newOrderItems = [
            ...$oldOrderItems,
            [
                'product_id' => $productId,
                'count'      => $count,
                'price'      => $price
            ]
        ];

        $updatedCart = [
            'total_price' => ($oldTotalPrice + $newAddedAmount),
            'total_count' => ($oldTotalCount + $count),
            'user_id'     => $oldCart['user_id'],
            'order_items' => $newOrderItems
        ];

        return $this->setCart($updatedCart);
    }

    public function removeProduct(string $productId): bool
    {
        if( ! $this->ItemExistsInCart($productId)) {
            throw new Exception('item not found');
        }

        $oldCart = $this->getCart();
        $oldOrderItems = $oldCart['order_items'];
        $index = $this->getItemIndexInCart($productId);

        // total product price
        $productCount = (int)$oldOrderItems[$index]['count'];
        $productPrice = (int)$oldOrderItems[$index]['price'];
        $totalProductPrice = (int)($productCount * $productPrice);

        // remove product
        unset($oldOrderItems[$index]);

        $updatedCart = [
            'total_price' => ((int)$oldCart['total_price'] - $totalProductPrice),
            'total_count' => ((int)$oldCart['total_count'] - $productCount),
            'user_id'     => $oldCart['user_id'],
            'order_items' => $oldOrderItems
        ];
        return $this->setCart($updatedCart);
    }

    /**
     * @param string $operation Can be "increase" or "decrease"
     */
    public function changeProductCountBy(array $data, string $operation): bool
    {
        // new data
        $productId = $data['product_id'];
        $count = (int)$data['count'];
        $newPrice = (int)$data['price'];

        if( ! $this->ItemExistsInCart($productId)) {
            throw new Exception('item not found');
        }


        // old data (existing data in the cart)
        $oldCart = $this->getCart();
        $oldTotalPrice = $oldCart['total_price'];

        $index = $this->getItemIndexInCart($productId);
        $oldProductCount = (int)$oldCart['order_items'][$index]['count'];
        $oldProductPrice = (int)$oldCart['order_items'][$index]['price'];


        // it is possible that product's price is changed, so update data in the cart
        if($operation == 'increase') {
            $newProductCount = $oldProductCount + $count;
            $newTotalPrice = (($newPrice * $newProductCount) + ($oldTotalPrice - ($oldProductCount * $oldProductPrice)));
            $newTotalCount = $oldCart['total_count'] + $count;

        } else {

            $newProductCount = $oldProductCount - $count;
            $newTotalPrice = (($newPrice * $newProductCount) + ($oldTotalPrice - ($oldProductCount * $oldProductPrice)));
            $newTotalCount = $oldCart['total_count'] - $count;
        }

        // update order_items
        if($newProductCount <= 0) {

            unset($oldCart['order_items'][$index]);

        } else {

            $oldCart['order_items'][$index] = [
                'product_id' => $productId,
                'count'      => $newProductCount,
                'price'      => $newPrice,
            ];
        }


        $updatedCart = [
            'total_price' => $newTotalPrice,
            'total_count' => $newTotalCount,
            'user_id'     => $oldCart['user_id'],
            'order_items' => $oldCart['order_items']
        ];

        return $this->setCart($updatedCart);
    }

    public function payment(): bool
    {
        $cart = $this->getCart();
        if(empty($cart) || empty($cart['order_items'])) {
            throw new \Exception('empty cart');
        }

        // check the inventory for all products in the cart before redirect to gateway
        $updatedCart = $this->checkInventory($cart);

        // go to payment gateway ...
        // if payment was successful, then redirect to `orderProcessing` route and show the "rahgiri info" to client
        return $this->orderProcessing($updatedCart, "abcd");
    }

    public function orderProcessing(array $cart, string $codeRahgiri): bool
    {
        try {

            DB::beginTransaction();

            //decrease inventory
            $this->updateInventory($cart);

            // save order in db
            $this->orderRepository->store([
                'total_price'  => $cart['total_price'],
                'total_count'  => $cart['total_count'],
                'user_id'      => $cart['user_id'],
                'order_items'  => $cart['order_items'],
                'rahgiri_code' => $codeRahgiri
            ]);


            DB::commit();
            $this->removeCart();
            return $codeRahgiri;

        } catch(\Exception $e) {
            DB::rollback();
            Log::error('Order processing error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    private function checkInventory(array &$cart): array
    {
        foreach($cart['order_items'] as $item) {
            $product = $this->productRepository->find($item['product_id']);
            $stock = $product->total_available;

            if($stock <= 0) {
                throw new \Exception('item out of stock. product_id: ' . $item['product_id']);
            }

            if($stock < $item['count']) {
                $diff = (int)($item['count'] - $stock);
                $item['count'] = $stock;
                $cart['total_count'] -= $diff;
                $cart['total_price'] -= ($diff * $item['price']);
            }
        }

        return $cart;
    }

    private function updateInventory(array $cart): bool
    {
        try {

            DB::beginTransaction();

            foreach($cart['order_items'] as $item) {
                $product = $this->productRepository->find($item['product_id']);

                foreach($product['inventory'] as $index => $inventoryStock) {

                    if( $item['count'] <= $inventoryStock['count'] ) {
                        $product['inventory'][$index]['count'] -= $item['count'];


                    } else {
                        $item['count'] -= $inventoryStock['count'];
                        $inventoryStock['count'] = 0;
                    }


                    if($product['inventory'][$index]['count'] <=0) {
                        unset($product['inventory'][$index]);
                    }

                    if($item['count'] == 0) {
                        break;
                    }
                }

                // update product
                $product->update([
                    'inventory' => json_encode($product['inventory'])
                ]);
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Inventory updating while payment error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }

    }
}
