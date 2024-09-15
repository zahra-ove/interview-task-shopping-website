<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function ($collection) {
            $collection->string('name')->unique();
            $collection->double('price');
            $collection->array('inventory')->nullable();   // array of nested
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
