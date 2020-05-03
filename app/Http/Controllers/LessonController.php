<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Course;
use App\Http\Requests\StoreLesson;
use App\Http\Requests\StoreVideo;
use Illuminate\Support\Facades\Storage;
use App\Jobs\RotateAndCropImage;
use App\Jobs\ConvertVideoForStreaming;
use App\Video;

class LessonController extends Controller
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

    public function new(Course $course) {
        return view('lesson.new', compact('course'));
    }

    public function create(StoreLesson $request) {

      $video = Video::create([
        'disk'              => 'public',
        'video_path'        => $request->video->store('video/original', 'public'),
      ]);

      $this->dispatch(new ConvertVideoForStreaming($video));

      return Lesson::create([
          'title' => $request->title,
          'instructions' => $request->instructions,
          'video_id' => $video->id,
          'course_id' => $request->course_id,
      ]);

    }

    /**
     * Show a single lesson
     *
     * @return \Illuminate\Http\Response
     */

    public function single(Lesson $lesson) {
        return view('lesson.single', compact('lesson'));
    }

    /**
     * Edit a single lesson
     *
     * @return \Illuminate\Http\Response
     */

    public function edit(Lesson $lesson) {
        return view('lesson.edit', compact('lesson'));
    }
}
