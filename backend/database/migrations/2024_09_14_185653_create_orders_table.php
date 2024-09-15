<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function ($collection) {
            $collection->integer('total_count');
            $collection->double('total_price');
            $collection->array('order_items');  // array of nested documents
            $collection->string('rahgiri_code')->nullable();

            $collection->string('user_id');
            $collection->index('user_id', 'order_user_indx');

            $collection->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
