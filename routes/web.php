<?php

use Illuminate\Support\Facades\Route;

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/
Route::get('/', 'MoodleController@getCourses')->name('courses');
Route::get('/users', 'MoodleController@getUsers')->name('users');
Route::get('/user_courses', 'MoodleController@getUserCourses')->name('user_courses');