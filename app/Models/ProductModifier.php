<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModifier extends Model
{
    use HasFactory;
    protected $table = 'product_modifiers';
    protected $fillable = [
        'product_id',
        'name_ar',
        'name_en',
        'details_ar',
        'details_en',
        'count',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
    public function options()
    {
        return $this->hasMany(ProductOption::class , 'modifier_id');
    }

}
