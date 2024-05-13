<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable=[
        'bearer_token',
        'sender_name',
        'commission',
        'contact_number',
        'coltd_token',
        'delivery_price',
        'search_range',
        'advisor_number',
        'tax',
        'myFatoourah_token',
        'contact_text',
        'email',
        'customer_services_number',
    ];
}
