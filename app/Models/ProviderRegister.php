<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderRegister extends Model
{
    protected $table = 'providers_registers';
    protected $fillable = [
        'name',
        'phone_number',
        'store_name',
        'city',
        'district',
        'street',
        'activity_type',
        'email',
        'url',
        'status',                    // new  , completed , canceled
        'cancel_reason',

    ];
}
