<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteProvider extends Model
{
    use HasFactory;
    protected $table = 'favorite_providers';
    protected $fillable = [
        'user_id',
        'provider_id',
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
