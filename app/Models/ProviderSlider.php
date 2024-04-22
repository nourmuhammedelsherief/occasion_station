<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderSlider extends Model
{
    protected $table = 'provider_sliders';
    protected $fillable = [
        'provider_id',
        'photo',
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
}
