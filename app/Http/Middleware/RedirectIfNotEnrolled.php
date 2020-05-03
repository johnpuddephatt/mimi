<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotEnrolled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      if(
        Auth::User()->is_admin
        || ($request->route('course') && Auth::User()->courses()->find($request->route('course')->id))
        || ($request->route('lesson') && Auth::User()->courses()->find($request->route('lesson')->course->id))
      ){
        return $next($request);
      }
      return redirect(RouteServiceProvider::HOME);
    }
}
