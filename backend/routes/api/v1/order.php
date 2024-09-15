<?php

use App\Http\Controllers\Api\V1\Store\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('store/order')->as('store.order.')->group(function () {

    Route::post('/add-product', [OrderController::class, 'addProduct'])->name('add.product');
    Route::post('/remove-product', [OrderController::class, 'removeProduct'])->name('remove.product');
    Route::post('/increase-product', [OrderController::class, 'increaseProductCount'])->name('increase.product');
    Route::post('/decrease-product', [OrderController::class, 'decreaseProductCount'])->name('decrease.product');
    Route::get('/pay-order', [OrderController::class, 'pay'])->name('pay.order');

});
