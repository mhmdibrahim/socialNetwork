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

// Showing Posts pages - Posting - Liking Posts - Unliking - Sharing Posts - Deleting Posts
// Showing post comments - Commenting  - Likeing Comments - Unliking -Deleting Comments
//
// /{id}/posts -> PostsController@index
// /posts => PostsController@store
// /{id}/like -> PostsController@like
// /[id}/unlike -> PostsController@unlike
// /{id}/share -> PostsController@share
// /{id}/delete -> PostsController@delete

// /posts/{/id}/comments -> CommentsController@index


Auth::routes();
Route::get('/', 'HomeController@index')->name('home');

Route::prefix('requests')->group(function (){
    Route::get('/','FriendRequestController@index');
    Route::post('{to_id}/send', 'FriendRequestController@send');
    Route::post('{to_id}/cancel', 'FriendRequestController@cancel');
    Route::post('{from_id}/accept', 'FriendRequestController@accept');
    Route::post('{from_id}/reject', 'FriendRequestController@reject');
});
Route::prefix('profile')->group(function (){
    Route::get('edit','ProfileController@edit');
    Route::post('update','ProfileController@update');
    Route::get('{user_id}','ProfileController@index');
});


Route::prefix('posts')->group(function (){
    Route::post('create','PostController@create');
    Route::post('{post_id}/like','PostController@like');
    Route::post('{post_id}/unlike','PostController@unlike');
    Route::post('{post_id}/delete','PostController@delete');
    Route::get('{user_id}/{post_id}/likes','PostController@showLikes');
    Route::get('{user_id}','PostController@index');
    Route::get('{user_id}/{post_id}/comments','PostController@showComments');
    Route::post('{user_id}/{post_id}/share','PostController@share');

});

Route::prefix('comments')->group(function (){
    Route::post('/add','CommentController@add');
    Route::post('/{comment_id}/delete','CommentController@delete');
    Route::get('{user_id}/{comment_id}/post/{post_id}/likes','CommentController@showLikes');
    Route::post('{comment_id}/post/{post_id}/like','CommentController@like');
    Route::post('{like_id}/unlike','CommentController@unlike');
});

Route::get('/friends','UserController@showFriends');
Route::get('/{id}/friend/delete','UserController@deleteFriend');
Route::get('{id}/notifications','UserController@notifications');

Route::prefix('testapi')->namespace('API')->group(function (){
    Route::prefix('profile')->group(function (){
        Route::get('edit','ProfileController@edit');
        Route::post('update','ProfileController@update');
        Route::get('{user_id}','ProfileController@index');
    });
});
