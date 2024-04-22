<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = [
        'name',
        'code',
        'google_city_id'
    ];

    public function providers()
    {
        return $this->hasMany(Provider::class , 'city_id');
    }
    public function users()
    {
        return $this->hasMany(User::class , 'city_id');
    }
}
