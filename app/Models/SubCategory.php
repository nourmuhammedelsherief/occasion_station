<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = [
        'category_id',
        'name',
        'name_en',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }
    public function provider_sub_categories()
    {
        return $this->hasMany(ProviderCategory::class , 'sub_category_id');
    }
}
