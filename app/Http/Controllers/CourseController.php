<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\Http\Requests\StoreCourse;

class CourseController extends Controller
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

    public function new() {
        return view('course.new');
    }

    public function create(StoreCourse $request) {
      $course = Course::create($request->all());
      return redirect()->route('course.edit', ['course' => $course->id]);
    }

    /**
     * Show a single course
     *
     * @return \Illuminate\Http\Response
     */

    public function single(Course $course) {
        return view('course.single', compact('course'));
    }

    /**
     * Edit a single course
     *
     * @return \Illuminate\Http\Response
     */

    public function edit(Course $course) {
        return view('course.edit', compact('course'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(\Auth::User()->is_admin) {
        $courses = Course::all();
        return view('home', compact('courses'));
      }
      else {
        $courses = \Auth::User()->courses;
        if($courses->count() == 1) {
          return redirect()->route('course.single', ['course' => $courses->first()]);
        }
        else {
          return view('home', compact('courses'));
        }
      }
    }
}
