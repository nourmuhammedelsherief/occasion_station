<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminOrderHistory extends Model
{
    protected $table = 'admin_order_histories';
    protected $fillable = [
        'admin_id',
        'redirection_admin',
        'order_id',
        'notes',
        'title'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class , 'admin_id');
    }
    public function redirect()
    {
        return $this->belongsTo(Admin::class , 'redirection_admin');
    }
    public function order()
    {
        return $this->belongsTo(Cart::class , 'order_id');
    }
}
