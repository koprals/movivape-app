<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProbabilitiesTable extends Migration
{
    public function up()
    {
        Schema::create('probabilities', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->decimal('probability', 15, 10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('probabilities');
    }
}

