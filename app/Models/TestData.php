<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestData extends Model
{
    use HasFactory;

    protected $table = 'test_data';
    protected $fillable = [
        'order_date', 'no_order', 'product_name', 'product_price',
        'product_qty', 'status', 'predicted_status', 'gaussian'
    ];
}

