<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table = 'user_notifications';
    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'title',
        'message',
        'device_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function order()
    {
        return $this->belongsTo(Cart::class , 'order_id');
    }
}
