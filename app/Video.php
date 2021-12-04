<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'video_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_subjects_mapped_id', 'title', 'description', 'video_root_name', 'thumbnail_name', 'quiz_file_name', 'video_type', 'status', 'created_by', 'updated_by'
    ];

    /**
     * Get the class and subject id for video's
     */
    public function getClassSubject()
    {
        return $this->hasOne('App\ClassSubjectsMapped', 'id', 'class_subjects_mapped_id');
    }

    /**
     * Get the class and subject id for video's
     */
    public function VideoWatchDetails()
    {
        return $this->hasOne('App\VideoWatch', 'video_id');
    }
}
