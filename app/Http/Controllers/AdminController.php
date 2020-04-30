<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Course;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show a single course
     *
     * @return \Illuminate\Http\Response
     */

    public function overview() {
      $courses = Course::all();
      return view('admin.dashboard', compact('courses'));
    }
}
