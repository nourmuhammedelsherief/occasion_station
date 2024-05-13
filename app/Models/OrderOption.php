<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOption extends Model
{
    use HasFactory;
    protected $table = 'order_options';
    protected $fillable = [
        'order_id',
        'option_id',
        'option_count',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class , 'order_id');
    }
    public function option()
    {
        return $this->belongsTo(ProductOption::class , 'option_id');
    }
}
