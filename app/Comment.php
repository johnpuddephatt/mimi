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
        'parent_id',
        'user_id'
    ];

    protected $casts = [
      'user_id' => 'integer',
      'lesson_id' => 'integer',
      'parent_id' => 'integer',
      'video_id' => 'integer'
    ];

    protected static function boot() {
      parent::boot();
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

    public function parent()
    {
      return $this->belongsTo('App\Comment');
    }

    public function video()
    {
      return $this->belongsTo('App\Video');
    }

}
