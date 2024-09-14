<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->decimal('price', 15,2);

            $table->unsignedInteger('inventory_id')->nullable();
            $table->index('inventory_id', 'product_inventory_indx');
            $table->foreign('inventory_id', 'product_inventory_fk')->references('id')->on('inventories')->onDelete('set null')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
