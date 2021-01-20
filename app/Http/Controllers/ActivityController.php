<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
  public function index()
  {
    $activities = Activity::orderBy('id','DESC')->simplePaginate(12);
    return view('activity', compact('activities'));
  }
}
