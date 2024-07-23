<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    // Disable Laravel's default timestamps
    protected $fillable = [
        'order_id',
        'product_id',
        'product_qty',
        'total'
    ];
    public $timestamps = false;

    // Define the fields that should be cast to dates
    protected $dates = ['createdon', 'modifiedon'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderDetail) {
            $orderDetail->createdon = $orderDetail->freshTimestamp();
            $orderDetail->modifiedon = $orderDetail->freshTimestamp();
        });

        static::updating(function ($orderDetail) {
            $orderDetail->modifiedon = $orderDetail->freshTimestamp();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
