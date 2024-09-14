<?php

use Illuminate\Support\Facades\Route;

require base_path('routes/api/v1/auth.php');

Route::middleware(['auth:jwt'])->prefix('v1')->as('v1:')->group(function(){

});
