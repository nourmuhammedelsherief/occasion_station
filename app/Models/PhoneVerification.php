<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    //
    protected $table='phone_verification';
    public $timestamps = true;
    public $primaryKey = 'id';
    protected $fillable = [
        'code', 'phone_number',
    ];
}
