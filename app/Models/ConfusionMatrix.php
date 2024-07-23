<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfusionMatrix extends Model
{
    use HasFactory;

    protected $table = 'confusion_matrix';
    protected $fillable = ['accuracy', 'precision', 'recall', 'f1_score', 'auc', 'f_rate'];
}

