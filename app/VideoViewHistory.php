<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoViewHistory extends Model
{
    protected $table = 'video_view_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'video_id'
    ];
}
