<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderRate extends Model
{
    use HasFactory;
    protected $table = 'provider_rates';
    protected $fillable = [
        'user_id',
        'provider_id',
        'rate',
        'rate_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
}
