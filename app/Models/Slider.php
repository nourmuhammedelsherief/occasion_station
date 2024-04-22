<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders';
    protected $fillable = [
        'title' ,
        'photo',
        'provider_id',
        'product_id',
        'outer_url',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
}
