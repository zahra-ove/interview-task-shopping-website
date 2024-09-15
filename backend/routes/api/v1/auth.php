<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('')->as('auth.')->group(function() {

    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');

});
