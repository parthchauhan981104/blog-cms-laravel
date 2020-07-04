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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Automatically match all routes to category controller methods
Route::resource('categories', 'CategoriesController');
Route::resource('posts', 'PostsController');

Route::get('trashed-posts', 'PostsController@trashed')->name('trashed-posts.index');

Route::put('restore-post/{post}', 'PostsController@restorePost')->name('restore-post'); //using get is not good for this as then anyone could restore any post

