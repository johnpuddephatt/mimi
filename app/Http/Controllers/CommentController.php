<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Lesson;
use App\Video;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ConvertVideoForStreaming;

class CommentController extends Controller
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

    /**
     * Create a new course
     *
     * @return \Illuminate\Http\Response
     */


    public function create(Lesson $lesson, StoreComment $request) {

      $video = Video::create([
        'disk'              => 'public',
        'video_path'        => $request->video->store('video/original', 'public'),
      ]);

      $this->dispatch(new ConvertVideoForStreaming($video));

      return Comment::create([
          'type' => $request->type,

          'user_id' => $request->user_id,
          'comment_id' => $request->comment_id,
          'lesson_id' => $request->comment_id ? null : $request->lesson_id,

          'video_id' => $video->id,
      ]);
    }
}
