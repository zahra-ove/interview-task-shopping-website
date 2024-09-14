<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Repositories\V1\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->userRepository->store($request->only(['name', 'email', 'password']));
        $token = Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], HttpResponse::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user'   => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
