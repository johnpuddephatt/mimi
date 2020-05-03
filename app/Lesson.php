<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{

    protected $fillable = [
        'title',
        'instructions',
        'video_id',
        'course_id'
    ];


    public function video()
    {
      return $this->belongsTo('App\Video');
    }

    public function course()
    {
      return $this->belongsTo('App\Course');
    }
}
