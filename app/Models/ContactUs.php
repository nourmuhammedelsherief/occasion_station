<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_us';
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'title',
        'message',
        'reply',
        'user_id',
        'archived',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
