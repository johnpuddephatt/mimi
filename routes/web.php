<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'CourseController@index')->middleware('auth');

Route::post('log', function (\Illuminate\Http\Request $request) {
  Log::channel('frontend')->info($request->error);
});

Route::get('course/{course}', 'CourseController@single')->name('course.single')->middleware('enrolled');
Route::get('course/{course}/lesson/{lesson}/reply/{reply_id}/{show_feedback?}', 'LessonController@single')->name('lesson.reply')->middleware('enrolled');
Route::get('course/{course}/lesson/{lesson}', 'LessonController@single')->name('lesson.single')->middleware('enrolled');
Route::post('lesson/{lesson}/reply', 'ReplyController@create')->name('reply.create')->middleware('enrolled');
Route::get('lesson/{lesson}/video/{video}', 'VideoController@single')->name('video.single')->middleware('enrolled');

Route::post('lesson/{lesson}/reply/{reply}/comment', 'CommentController@create')->name('comment.create')->middleware('enrolled');
Route::get('lesson/{lesson}/reply/{reply}/comments', 'CommentController@index')->name('comment.index')->middleware('enrolled');

Route::get('admin', 'AdminController@overview')->name('admin')->middleware('admin');
Route::get('admin/course/new', 'CourseController@new')->name('course.new')->middleware('admin');
Route::post('admin/course/create', 'CourseController@create')->name('course.create')->middleware('admin');
Route::get('admin/course/{course}', 'CourseController@edit')->name('course.edit')->middleware('admin');
Route::put('admin/course/{course}', 'CourseController@update')->name('course.update')->middleware('admin');
Route::delete('admin/course/{course}', 'CourseController@delete')->name('course.delete')->middleware('admin');

Route::get('admin/course/{course}/lesson/new', 'LessonController@new')->name('lesson.new')->middleware('admin');
Route::post('admin/course/{course}/lesson/create', 'LessonController@create')->name('lesson.create')->middleware('admin');
Route::get('admin/course/{course}/lesson/{lesson}', 'LessonController@edit')->name('lesson.edit')->middleware('admin');
Route::put('admin/course/{course}/lesson/{lesson}', 'LessonController@update')->name('lesson.update')->middleware('admin');
Route::delete('admin/course/{course}/lesson/{lesson}', 'LessonController@delete')->name('lesson.delete')->middleware('admin');


Route::get('admin/emails', function() {
  return view('admin.emails');
})->name('admin.emails')->middleware('admin');

Route::get('admin/emails/newreply', function () {
    $reply = App\Reply::whereNull('reply_id')->latest()->first();
    abort_if(!$reply, 404);
    return new App\Mail\NewReply($reply);
})->name('admin.emails.newreply')->middleware('admin');;
Route::get('admin/emails/newreplyfeedback', function () {
    $reply = App\Reply::whereNotNull('reply_id')->latest()->first();
    abort_if(!$reply, 404);
    return new App\Mail\NewFeedback($reply);
})->name('admin.emails.newfeedback')->middleware('admin');;

Route::get('admin/emails/newcomment', function () {
    $comment = App\Comment::latest()->first();
    abort_if(!$comment, 404);
    return new App\Mail\NewComment($comment);

})->name('admin.emails.newcomment')->middleware('admin');;
