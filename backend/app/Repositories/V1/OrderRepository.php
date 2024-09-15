<?php

namespace App\Repositories\V1;

use App\Models\Order;
use App\Repositories\V1\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function store(array $order): null|Order
    {

    }
}
