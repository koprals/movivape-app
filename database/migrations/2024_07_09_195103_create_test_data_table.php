<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestDataTable extends Migration
{
    public function up()
    {
        Schema::create('test_data', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');
            $table->string('no_order');
            $table->string('product_name');
            $table->decimal('product_price', 10, 2);
            $table->integer('product_qty');
            $table->string('status');
            $table->string('predicted_status');
            $table->decimal('gaussian', 15, 10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_data');
    }
}

