<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProductCategory extends Model
{
    protected $table = 'provider_product_categories';
    protected $fillable = [
        'provider_id',
        'category_id',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
    public function category()
    {
        return $this->belongsTo(ProductCategory::class , 'category_id');
    }
}
