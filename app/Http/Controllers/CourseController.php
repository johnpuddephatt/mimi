<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;

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
     * Show a single course
     *
     * @return \Illuminate\Http\Response
     */

    public function single(Course $course) {
      if(\Auth::User()->courses())
      return view('course.single', compact('course'));
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
        $enrolled_course_count = \Auth::User()->courses()->count();
        if($enrolled_course_count < 1) {
            return view('home');
        }
        elseif($enrolled_course_count > 1) {
          \Auth::User()->courses()->count();
          return view('home', compact('courses'));
        }
        else {
          return redirect()->route('course', [\Auth::User()->courses()->first()]);
        }
      }
    }
}
