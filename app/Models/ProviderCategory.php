<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderCategory extends Model
{
    protected $table = 'provider_categories';
    protected $fillable = [
        'provider_id',
        'sub_category_id',
        'active',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class , 'sub_category_id');
    }
}
