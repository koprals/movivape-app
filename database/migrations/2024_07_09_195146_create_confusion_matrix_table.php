<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfusionMatrixTable extends Migration
{
    public function up()
    {
        Schema::create('confusion_matrix', function (Blueprint $table) {
            $table->id();
            $table->decimal('accuracy', 5, 4);
            $table->decimal('precision', 5, 4);
            $table->decimal('recall', 5, 4);
            $table->decimal('f1_score', 5, 4);
            $table->decimal('auc', 5, 4);
            $table->decimal('f_rate', 5, 4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('confusion_matrix');
    }
}

