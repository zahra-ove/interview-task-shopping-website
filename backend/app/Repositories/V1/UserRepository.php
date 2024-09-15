<?php

namespace App\Repositories\V1;

use App\Models\User;
use App\Repositories\V1\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function store(array $userData): User|null
    {
        return User::create([
            'name'     => $userData['name'],
            'email'    => $userData['email'],
            'password' => Hash::make($userData['password']),
        ]);
    }
}
