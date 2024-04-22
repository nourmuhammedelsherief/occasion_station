<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderCommissionHistory extends Model
{
    protected $table = 'provider_commission_histories';
    protected $fillable = [
        'provider_id',
        'amount',
        'transfer_photo',
        'invoice_id',
        'status',   // wait , done , canceled
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class , 'provider_id');
    }
}
