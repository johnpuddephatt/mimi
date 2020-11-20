<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Reply;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatController extends Controller
{
  public function index()
  {
    return view('stats');
  }

  public function repliesMonthly($from, $to) {
    $replies_by_month = Reply::select('created_at')->whereBetween('created_at',[date($from),date($to)])
            ->get()
            ->groupBy(function($val) {
              return Carbon::parse($val->created_at)->format('M');
            });

    $counts = [];

    foreach($replies_by_month as $month => $replies) {
      $counts[$month] = $replies->count();
    }

    return $counts;
  }

  public function teacherMonthly($from, $to) {
    return User::select('first_name', 'last_name')->where('is_admin',true)->withCount(['replies' => function (Builder $query) use ($from, $to) {
      $query->whereBetween('created_at',[date($from),date($to)]);
    }])->get();
  }
}
