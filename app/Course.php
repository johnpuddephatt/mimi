<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{

  protected $fillable = [
      'title', 'description'
  ];

  protected $with = ['lessons'];

  public function hash() {
    return \Hashids::encode($this->id);
  }

  public function users()
  {
    return $this->belongsToMany('App\User', 'enrolments');
  }

  public function lessons()
  {
    return $this->hasMany('App\Lesson');
  }
}
