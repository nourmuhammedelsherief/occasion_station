<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Provider extends Authenticatable
{
    use Notifiable;
//    use EntrustUserTrait;
    protected $guard = 'provider';

    protected $table = 'providers';
    protected $fillable = [
        'name',
        'activity',    // rent , sale , both
        'phone_number',
        'email',
        'password',
        'photo',
        'category_id',  // the main category that provider Use
        'city_id',
        'latitude',
        'longitude',
        'address',
        'special',
        'show_cart',
        'vip',
        'stop',
        'logo',
        'arrange',
        'provider_category_arrange',
        'description',
        'description_en',
        'commission',  // the total commissions for provider
        'bank_payment',
        'online_payment',
        'tamara_payment',
        'rate',
        'delivery',
        'delivery_price',
        'delivery_by',
        'store_receiving',
        'name_en',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class , 'city_id');
    }
    public function products()
    {
        return $this->hasMany(Product::class , 'provider_id');
    }
    public function provider_categories()
    {
        return $this->hasMany(ProviderCategory::class , 'provider_id');
    }
    public function provider_main_categories()
    {
        return $this->hasMany(ProviderMainCategory::class)->orderBy('arrange');
    }
    public function sliders()
    {
        return $this->hasMany(ProviderSlider::class , 'provider_id');
    }
    public function favorites()
    {
        return $this->hasMany(FavoriteProvider::class , 'provider_id');
    }
    public function rates()
    {
        return $this->hasMany(ProviderRate::class , 'provider_id');
    }
}
