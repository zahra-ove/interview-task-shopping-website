<?php

namespace App\Repositories\V1\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function store(array $userData): User|null;
}
