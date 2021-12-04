<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserAccount extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_account';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'email_verified_at', 'mobile_number', 'password', 'remember_token', 'api_token', 'password_reset_token', 'password_reset_at', 'class_id', 'user_type', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the subjects Name from App\Subjects
     */
    public function usersLoginHistory()
    {
        return $this->hasOne('App\UserLoginHistory', 'user_id')->latest();
    }

    /**
     * Get the user that belongsTo to class
     */
    public function userClassName()
    {
        return $this->belongsTo('App\Classes', 'class_id');
    }

    /**
     * Get the details if user paid class fee.
     */
    public function classFeePaidUser()
    {
        return $this->hasMany('App\Payment', 'user_id');
    }
}
