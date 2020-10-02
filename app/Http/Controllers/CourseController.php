<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;
use App\User;
use App\Http\Requests\StoreCourse;
use App\Providers\RouteServiceProvider;

class CourseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
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

    public function update(Course $course, StoreCourse $request) {
      $course->update($request->all());
      return view('course.edit', compact('course'));
    }

    public function enrollCurrentUser($course) {
      if (\Auth::guard()->check()) {

        if(\Auth::User()->courses->contains(\Hashids::decode($course)[0])) {
          return redirect(RouteServiceProvider::HOME)->with('message', 'You are already enrolled in this course');
        }
        else {
          \Auth::User()->courses()->attach(\Hashids::decode($course));
          return redirect(RouteServiceProvider::HOME)->with('message', 'You have been enrolled.');
        }
      }
      else {
        return view('enroll', compact('course'));
      }
    }

    public function enroll(Course $course, Request $request) {
      return $course->users()->attach($request->all());
    }

    public function unenroll(Course $course, User $user) {
      $user->courses()->detach($course->id);
      return back();
    }

    public function users(Course $course) {
      return $course->users;
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
