<?php

use App\Http\Controllers\Api\V1\Store\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('store/products', [ProductController::class, 'index'])->name('store.products.index');
Route::get('store/products/{id}', [ProductController::class, 'show'])->name('store.products.show');
