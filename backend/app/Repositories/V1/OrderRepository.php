<?php

namespace App\Repositories\V1;

use App\Models\Order;
use App\Repositories\V1\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function store(array $order): null|Order
    {
        return Order::create([
            'total_price'  => $order['total_price'],
            'total_count'  => $order['total_count'],
            'user_id'      => $order['user_id'],
            'order_items'  => $order['order_items'],
            'rahgiri_code' => $order['rahgiri_code'],
        ]);
    }
}
