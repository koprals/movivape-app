<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnalysisResult extends Model
{
    protected $fillable = [
        'time_range', 'time_value', 'transaction_patterns', 'product_frequency', 'suggested_products'
    ];
}
