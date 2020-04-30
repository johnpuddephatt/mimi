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

  public function link() {
    return '/course/' . $this->id;
  }

  public function users()
  {
    return $this->belongsToMany('App\User', 'enrolments');
  }
}
