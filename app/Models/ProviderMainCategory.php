<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderMainCategory extends Model
{
    protected $table = 'providers_main_categories';
    protected $fillable = [
        'provider_id',
        'category_id',
        'arrange',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }
}
