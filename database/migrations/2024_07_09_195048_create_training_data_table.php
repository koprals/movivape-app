<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingDataTable extends Migration
{
    public function up()
    {
        Schema::create('training_data', function (Blueprint $table) {
            $table->id();
            $table->date('order_date');
            $table->string('no_order');
            $table->string('product_name');
            $table->decimal('product_price', 10, 2);
            $table->integer('product_qty');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_data');
    }
}
