<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'provider_id',
        'category_id',
        'activity',    // rent , sale
        'name',
        'description',
        'price_before_discount',
        'price',
        'less_amount',
        'product_requirements',
        'delivery',
        'recomended',
        'delivery_price',
        'delivery_by',
        'store_receiving',
        'stop',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class , 'product_id');
    }
}
