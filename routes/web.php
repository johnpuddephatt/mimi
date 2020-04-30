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
Route::get('/course/{course}', 'CourseController@single')->name('course')->middleware('auth','enrolled');
Route::get('/admin', 'AdminController@overview')->name('admin')->middleware('auth','admin');
// Route::get('count', 'VideoController@count');
// Route::post('upload', 'VideoController@store');
// Route::get('upload', 'VideoController@done');
// Route::get('/{channel?}', 'VideoController@index');
