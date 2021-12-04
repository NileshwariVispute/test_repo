<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoWatch extends Model
{
    protected $table = 'video_watching_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'video_id', 'continue_watching', 'continue_at'
    ];
}
