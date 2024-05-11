<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;
    protected $table = 'product_sizes';
    protected $fillable = [
        'product_id',
        'name_ar',
        'name_en',
        'price',
        'calories',
        'active',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id');
    }
}
