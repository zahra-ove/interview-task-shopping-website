<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function ($collection) {
            $collection->string('name', 255)->unique();
            $collection->double('price');
            $collection->string('inventory_id')->nullable();
            $collection->index('inventory_id', 'product_inventory_indx');
            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
