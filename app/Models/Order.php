<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['no_order'];

    // Disable Laravel's default timestamps
    public $timestamps = false;

    // Define the fields that should be cast to dates
    protected $dates = ['createdon', 'modifiedon'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $order->no_order = self::generateOrderNumber();
            $order->createdon = $order->freshTimestamp();
            $order->modifiedon = $order->freshTimestamp();
        });

        static::updating(function ($order) {
            $order->modifiedon = $order->freshTimestamp();
        });
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $randomNumber = mt_rand(1000, 9999);
        return $prefix . $date . $randomNumber;
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
