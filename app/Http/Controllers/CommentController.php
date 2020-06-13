<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use Illuminate\Support\Facades\Storage;

use App\Comment;
use App\Reply;
use App\Lesson;

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

    public function create(StoreComment $request, Lesson $lesson, Reply $reply) {
      return Comment::create($request->all());
    }

    public function index(Lesson $lesson, Reply $reply) {
      return $reply->comments;
    }
}
