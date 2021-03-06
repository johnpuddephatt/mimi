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
        'number',
        'video_id',
        'course_id'
    ];

    protected $casts = [
      'video_id' => 'integer',
      'course_id' => 'integer',
      'number' => 'integer'
    ];

    protected static function boot() {
      parent::boot();
      static::addGlobalScope('order', function (Builder $builder) {
        $builder->orderBy('number', 'asc');
      });
    }

    public function feedbackless_reply_count() {
      return $this->replies()->feedbackless()->count();
    }

    public function video()
    {
      return $this->belongsTo('App\Video');
    }

    public function course()
    {
      return $this->belongsTo('App\Course');
    }

    public function replies()
    {
      return $this->hasMany('App\Reply');
    }
}
