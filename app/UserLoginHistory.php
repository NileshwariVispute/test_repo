<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLoginHistory extends Model
{
    protected $table = 'users_login_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'ip_address'
    ];
}
