<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\AdminResetPasswordNotification;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone','admin_category_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_role')->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_permission')->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(AdminCategory::class , 'admin_category_id');
    }
    public function orders()
    {
        return $this->belongsTo(AdminOrder::class , 'admin_id');
    }


}
