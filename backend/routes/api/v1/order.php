<?php

use App\Http\Controllers\Api\V1\Store\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('store/order')->as('store.order.')->group(function () {

    Route::post('/add-product', [OrderController::class, 'addProduct'])->name('add.product');
    Route::get('/remove-product/{id}', [OrderController::class, 'removeProduct'])->name('remove.product');
    Route::post('/decrease-product', [OrderController::class, 'decreaseProductCount'])->name('decrease.product');
    Route::get('/payment', [OrderController::class, 'payment'])->name('pay.order');

});
