<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

  protected $fillable = [
      'title', 'description'
  ];

  public function hash() {
    return \Hashids::encode($this->id);
  }

  public function feedbackless_reply_count() {
    return $this->replies()->feedbackless()->count();
  }

  public function users()
  {
    return $this->belongsToMany('App\User', 'enrolments');
  }

  public function lessons()
  {
    return $this->hasMany('App\Lesson');
  }

  public function replies()
  {
    return $this->hasManyThrough('App\Reply', 'App\Lesson');
  }
}
