<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'provider_id',
        'user_id',
        'product_id',
        'size_id',
        'order_price',
        'options_price',
        'delivery_price',
        'status',         // 'on_cart','new_paid','new_no_paid','works_on','completed','canceled'
        'product_count',
        'delivery_date',
        'delivery_time',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_address',
        'more_details',
        'cart_id',
        'tax_value',
        'commission',  // the commission for completed orders
    ];
    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class , 'cart_id');
    }
    public function size()
    {
        return $this->belongsTo(ProductSize::class , 'size_id');
    }
    public function options()
    {
        return $this->hasMany(OrderOption::class , 'order_id');
    }
}
