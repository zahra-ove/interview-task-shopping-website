<?php

namespace App\Repositories\V1\Contracts;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function store(array $order): null|Order;
}
