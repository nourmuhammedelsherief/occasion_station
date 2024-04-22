<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminOrder extends Model
{
    protected $table = 'admin_orders';
    protected $fillable = [
        'admin_id',
        'order_id',
        'read'   // true , false
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class , 'admin_id');
    }

    public function order()
    {
        return $this->belongsTo(Cart::class , 'order_id');
    }

}
