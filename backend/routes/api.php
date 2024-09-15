<?php

use Illuminate\Support\Facades\Route;



Route::prefix('v1')->as('v1:')->group(function(){

    Route::middleware(['auth:api'])->group(function(){
        require base_path('routes/api/v1/product_admin.php');
    });

    require base_path('routes/api/v1/auth.php');
    require base_path('routes/api/v1/product_shop.php');
});
