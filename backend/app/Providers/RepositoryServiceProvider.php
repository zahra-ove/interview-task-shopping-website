<?php

namespace App\Providers;

use App\Repositories\V1\Contracts\UserRepositoryInterface;
use App\Repositories\V1\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
