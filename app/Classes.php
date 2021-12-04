<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'class_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_name', 'status', 'created_by', 'updated_by'
    ];

    /**
     * Get the ClassSubjectsMapped for the class subjects list.
     */
    public function classSubject()
    {
        return $this->hasMany('App\ClassSubjectsMapped', 'class_id');
    }

    /**
     * Get the details if user paid class fee.
     */
    public function classFeePaidUser()
    {
        return $this->hasOne('App\Payment', 'class_id')->latest();
    }
}
