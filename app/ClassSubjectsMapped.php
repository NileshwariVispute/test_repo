<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassSubjectsMapped extends Model
{
    protected $table = 'class_subjects_mapped';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_id', 'subject_id', 'status', 'created_by', 'updated_by'
    ];

    /**
     * Get the subjects Name from App\Subjects
     */
    public function classWiseSubjects()
    {
        return $this->belongsTo('App\Subjects', 'subject_id');
    }

    /**
     * Get the subjects Name from App\Subjects
     */
    public function videos()
    {
        return $this->hasMany('App\Video', 'class_subjects_mapped_id');
    }
}
