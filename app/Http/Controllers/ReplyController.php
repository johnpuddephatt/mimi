<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Reply;
use App\Lesson;
use App\Video;

use App\Http\Requests\StoreReply;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ConvertVideoForStreaming;

class ReplyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function delete(Lesson $lesson, Reply $reply) {
      $reply->delete();
    }

    /**
     * Create a new reply
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Lesson $lesson, StoreReply $request) {

      $video = Video::create([
        'disk'              => 'public',
        'video_path'        => $request->video->store('video/original', 'public'),
      ]);

      $this->dispatch(new ConvertVideoForStreaming($video));

      return Reply::create([
          'user_id' => $request->user_id,
          'reply_id' => $request->reply_id,
          'lesson_id' => $request->reply_id ? null : $request->lesson_id,
          'video_id' => $video->id,
      ]);

    }

}
