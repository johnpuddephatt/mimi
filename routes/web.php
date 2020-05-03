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
Route::get('/course/{course}', 'CourseController@single')->name('course.single')->middleware('enrolled');
Route::get('/lesson/{lesson}', 'LessonController@single')->name('lesson.single')->middleware('enrolled');

Route::get('admin', 'AdminController@overview')->name('admin')->middleware('admin');
Route::get('admin/course/new', 'CourseController@new')->name('course.new')->middleware('admin');
Route::post('admin/course/create', 'CourseController@create')->name('course.create')->middleware('admin');
Route::get('admin/course/{course}', 'CourseController@edit')->name('course.edit')->middleware('admin');
Route::patch('admin/course/{course}', 'CourseController@update')->name('course.update')->middleware('admin');
Route::delete('admin/course/{course}', 'CourseController@delete')->name('course.delete')->middleware('admin');

Route::get('admin/course/{course}/lesson/new', 'LessonController@new')->name('lesson.new')->middleware('admin');
Route::post('admin/lesson/create', 'LessonController@create')->name('lesson.create')->middleware('admin');
Route::get('admin/lesson/{lesson}', 'LessonController@edit')->name('lesson.edit')->middleware('admin');
Route::patch('admin/lesson/{lesson}', 'LessonController@update')->name('lesson.update')->middleware('admin');
Route::delete('admin/lesson/{lesson}', 'LessonController@delete')->name('lesson.delete')->middleware('admin');

// Route::get('count', 'VideoController@count');
// Route::post('upload', 'VideoController@store');
// Route::get('upload', 'VideoController@done');
// Route::get('/{channel?}', 'VideoController@index');
