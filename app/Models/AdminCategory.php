<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminCategory extends Model
{
    protected $table = 'admin_categories';
    protected $fillable = [
        'name',
    ];
    public function admins()
    {
        return $this->hasMany(Admin::class , 'admin_id');
    }


}
