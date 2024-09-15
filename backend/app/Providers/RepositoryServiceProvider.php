<?php

namespace App\Providers;

use App\Repositories\V1\Contracts\OrderRepositoryInterface;
use App\Repositories\V1\Contracts\ProductRepositoryInterface;
use App\Repositories\V1\Contracts\UserRepositoryInterface;
use App\Repositories\V1\OrderRepository;
use app\Repositories\V1\ProductRepository;
use App\Repositories\V1\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
