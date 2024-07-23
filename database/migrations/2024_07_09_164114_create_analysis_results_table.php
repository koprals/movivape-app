<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalysisResultsTable extends Migration
{
    public function up()
    {
        Schema::create('analysis_results', function (Blueprint $table) {
            $table->id();
            $table->string('time_range');
            $table->string('time_value');
            $table->json('transaction_patterns');
            $table->json('product_frequency');
            $table->json('suggested_products');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('analysis_results');
    }
}
