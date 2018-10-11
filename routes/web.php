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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::get('/requests','UserController@index');
Route::get('{id}/profile','UserController@profile');
Route::post('{id}/post/create','UserController@createPost');
Route::get('/request/{id}/accept','UserController@acceptRequest');
Route::get('/request/{id}/cancel','UserController@cancelRequest');
Route::get('/request/{id}/sent','HomeController@sentRequest');
Route::get('/request/{id}/cancelRequest','HomeController@cancelRequest');
Route::get('/friends','UserController@showFriends');
Route::get('/profile/edit','UserController@editMyProfile');
Route::post('/profile/edit','UserController@updateProfile');
Route::get('/{id}/posts','UserController@showposts');
Route::get('/{id}/post/delete','UserController@deletePost');
Route::get('/{id}/friend/delete','UserController@deleteFriend');
Route::post('/add/comment','UserController@addComment');
Route::get('/post/{id}/comments','UserController@showComments');
Route::get('/comment/{id}/delete','UserController@deleteComment');
Route::get('/post/{id}/likes','UserController@showLikes');
Route::get('/post/{id}/like','UserController@putLike');
Route::get('/post/{id}/unlike','UserController@unlike');
Route::get('/{id1}/comment/{id2}/likes','UserController@showCommentLikes');
Route::get('/{id1}/comment/{id2}/commentLike','UserController@likeComment');
Route::get('/comment/{id}/commentUnlike','UserController@unlikeComment');
Route::get('/user/{id1}/post/{id2}/share','UserController@share');
