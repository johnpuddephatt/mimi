<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Comment extends Model
{

    protected $fillable = [
        'type',
        'video_id',
        'lesson_id',
        'comment_id',
        'user_id'
    ];

    protected $casts = [
      'user_id' => 'integer',
      'lesson_id' => 'integer',
      'comment_id' => 'integer',
      'video_id' => 'integer'
    ];

    protected $with = ['user'];

    protected static function boot() {
      parent::boot();
      static::addGlobalScope('ready', function (Builder $builder) {
        $builder->has('video');
      });
      static::addGlobalScope('order', function (Builder $builder) {
        $builder->orderBy('id', 'desc');
      });
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function lesson()
    {
      return $this->belongsTo('App\Lesson');
    }

    public function comments()
    {
      return $this->hasMany('App\Comment');
    }

    public function video()
    {
      return $this->belongsTo('App\Video');
    }

}
