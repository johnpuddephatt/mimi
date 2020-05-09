<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Course;
use App\Video;
use App\Http\Requests\StoreLesson;
use App\Http\Requests\StoreVideo;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ConvertVideoForStreaming;

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
        $lesson_count = $course->lessons->count();
        return view('lesson.new', compact('course','lesson_count'));
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
          'number' => $request->number,
          'course_id' => $request->course_id,

          'video_id' => $video->id,
      ]);

    }

    public function update(StoreLesson $request, Course $course, Lesson $lesson) {

      // If we already have a video with the same playlist...
      if(!is_string($request->video)) {

        $video = Video::create([
          'disk'              => 'public',
          'video_path'        => $request->video->store('video/original', 'public'),
        ]);

        $this->dispatch(new ConvertVideoForStreaming($video));

        return $lesson->update([
            'title' => $request->title,
            'instructions' => $request->instructions,
            'number' => $request->number,
            'course_id' => $request->course_id,

            'video_id' => $video->id
        ]);
      }
      else {
        return $lesson->update([
            'title' => $request->title,
            'instructions' => $request->instructions,
            'number' => $request->number,
            'course_id' => $request->course_id
        ]);
      }


    }

    /**
     * Show a single lesson
     *
     * @return \Illuminate\Http\Response
     */

    public function single(Course $course, $lesson_id) {
      $lesson = Lesson::with('comments')->findOrFail($lesson_id);
      return view('lesson.single', compact('lesson'));
    }

    /**
     * Edit a single lesson
     *
     * @return \Illuminate\Http\Response
     */

    public function edit(Course $course, Lesson $lesson) {
        $lesson->video = Video::find($lesson->video_id)->playlist;

        return view('lesson.edit', compact('course', 'lesson'));
    }
}
