<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Disable Laravel's default timestamps
    public $timestamps = false;

    // Define the fields that should be cast to dates
    protected $dates = ['createdon', 'modifiedon'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->createdon = $product->freshTimestamp();
            $product->modifiedon = $product->freshTimestamp();
        });

        static::updating(function ($product) {
            $product->modifiedon = $product->freshTimestamp();
        });
    }

    // Define the relationship with OrderDetail if necessary
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
