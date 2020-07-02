<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Mail;
use App\Mail\NewComment;

class Comment extends Model
{
    protected $fillable = [
      'reply_id',
      'user_id',
      'value'
    ];

    protected $casts = [
      'user_id' => 'integer',
      'reply_id' => 'integer',
    ];

    protected $with = ['user'];

    protected static function boot() {
      parent::boot();

      static::created(function($comment){
        $comment->sendCommentNotification();
      });
    }

    public function user() {
      return $this->belongsTo('App\User');
    }

    public function reply() {
      return $this->belongsTo('App\Reply');
    }

    // A text comment from another student
    public function sendCommentNotification() {
      $email = new NewComment($this);
      $recipient = $this->reply->user;

      if($this->user->id != $recipient->id) {
        Mail::to($recipient)->send($email);
      }
    }

}
