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

Route::get('/course/{course}', 'CourseController@single')->name('course.single')->middleware('enrolled');
Route::get('/course/{course}/lesson/{lesson}', 'LessonController@single')->name('lesson.single')->middleware('enrolled');

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

Route::get('admin/video/{video}', 'VideoController@single')->name('video.single')->middleware('admin');

Route::post('admin/comment/create', 'CommentController@create')->name('comment.create')->middleware('admin');


// Route::get('count', 'VideoController@count');
// Route::post('upload', 'VideoController@store');
// Route::get('upload', 'VideoController@done');
// Route::get('/{channel?}', 'VideoController@index');
