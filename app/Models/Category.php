<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name' , 'name_en' , 'icon' , 'arrange'];

    public function sub_categories()
    {
        return $this->hasMany(SubCategory::class , 'category_id');
    }
    public function provider_categories()
    {
        return $this->hasMany(ProviderCategory::class , 'category_id');
    }
}
