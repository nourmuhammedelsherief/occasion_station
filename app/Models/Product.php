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
        'name_en',
        'description_en',
        'description',
        'price_before_discount',
        'price',
        'less_amount',
        'product_requirements',
        'product_requirements_en',
        'recomended',
        'stop',
        'accepted',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
    public function category()
    {
        return $this->belongsTo(ProductCategory::class , 'category_id');
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class , 'product_id');
    }
    public function favorites()
    {
        return $this->hasMany(FavoriteProduct::class , 'product_id');
    }
    public function sizes()
    {
        return $this->hasMany(ProductSize::class , 'product_id');
    }
    public function modifiers()
    {
        return $this->hasMany(ProductModifier::class , 'product_id');
    }
    public function options()
    {
        return $this->hasMany(ProductOption::class , 'product_id');
    }

}
