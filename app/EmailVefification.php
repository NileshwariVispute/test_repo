<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailVefification extends Model
{
    protected $table = 'email_verification_otp';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'otp', 'send_to'
    ];
}
