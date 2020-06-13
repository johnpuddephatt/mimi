<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\User;

use Mail;
use App\Mail\NewReply;
use App\Mail\NewFeedback;

class Reply extends Model
{

    protected $fillable = [
        'video_id',
        'lesson_id',
        'reply_id',
        'user_id'
    ];

    protected $casts = [
      'user_id' => 'integer',
      'lesson_id' => 'integer',
      'reply_id' => 'integer',
      'video_id' => 'integer'
    ];

    protected $with = ['user'];

    protected static function boot() {
      parent::boot();

      static::addGlobalScope('order', function (Builder $builder) {
        $builder->orderBy('id', 'desc');
      });

      static::addGlobalScope('video', function (Builder $builder) {
        $builder->has('video');
      });

      static::created(function($reply){
        if($reply->reply_id) {
          $reply->sendFeedbackNotification();
        }
        else {
          $reply->sendReplyNotification();
        }
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

    public function feedback()
    {
      return $this->hasOne('App\Reply');
    }

    public function reply()
    {
      return $this->belongsTo('App\Reply');
    }

    public function comments()
    {
      return $this->hasMany('App\Comment');
    }

    public function video()
    {
      return $this->belongsTo('App\Video');
    }

    // A top-level video comment from a student
    public function sendReplyNotification() {
      $email = new NewReply($this);
      $recipient = User::where('is_admin', true)->get();
      Mail::to($recipient)->send($email);
    }

    // Feedback video from an admin
    public function sendFeedbackNotification() {
      $email = new NewFeedback($this);
      $recipient = $this->reply->user;
      Mail::to($recipient)->send($email);
    }

}
