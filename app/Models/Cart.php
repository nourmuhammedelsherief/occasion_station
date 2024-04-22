<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';
    protected $fillable = [
        'user_id',
        'provider_id',
        'total_price',
        'payment_type',
        'transfer_photo',
        'invoice_id',
        'payment_status',
        'status',      // opened , sent , 'on_cart','new_paid','new_no_paid','works_on','completed','canceled'
        'delivery_date',
        'delivery_time',
        'delivery_latitude',
        'delivery_longitude',
        'delivery_address',
        'more_details',
        'items_price',
        'delivery_price',
        'tax_value',
        'tamara_payment',
        'tamara_order_id',
        'tamara_checkout_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class , 'cart_id');
    }
    public function admin_orders()
    {
        return $this->hasMany(AdminOrder::class , 'order_id');
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
}
