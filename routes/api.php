<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-industry-category', 'ApiController@getIndustryCategory');
Route::get('/get-category', 'ApiController@getCategory');

Route::get('/get-post-guideline', 'ApiController@getPostGuideline');


Route::get('/get-startupstage', 'ApiController@getStartupstage');
Route::any('/login-action', 'ApiController@loginAction');

Route::any('/user-list', 'ApiController@userList');
Route::any('/getall_user_list', 'ApiController@userList1');


Route::any('/logout-action', 'ApiController@logoutAction');

Route::post('/register-user', 'ApiController@saveUser');

Route::post('/dashboard', 'ApiController@dashboard');

// Route::post('/area-expertise-list', 'ApiController@expertiseList');

Route::post('/startup-list', 'ApiController@startupList');

Route::post('/category-post', 'ApiController@categoryPost');

Route::post('/my-post', 'ApiController@myPost');

Route::post('/feeds', 'ApiController@feedPost');

Route::post('/add-user', 'ApiController@addUser');

Route::post('/add-user1', 'ApiController@addUser1');

Route::post('/change-password', 'ApiController@changePassword');

Route::post('/test', 'ApiController@test');


Route::post('/last-login-post-count', 'ApiController@lastLoginPostCount');

Route::post('/last-login-post', 'ApiController@lastLoginPost');
//Route::post('/reset-password/{token}', 'ApiController@resetPasswordAction');

Route::post('/reset-pass', 'ApiController@resetPassword');
Route::post('/user-details', 'ApiController@userDetl');

Route::post('/update-user', 'ApiController@updateUser');

Route::post('/all-posts', 'ApiController@getAllPosts');
Route::post('/post-detl', 'ApiController@getPostDetl');


Route::post('/comment', 'ApiController@getComment');
Route::post('/comment-reply', 'ApiController@getCommentReply');

Route::post('/add-favourate', 'ApiController@addFavourate');
Route::post('/post-favourate', 'ApiController@postFavourate');

Route::post('/add-post', 'ApiController@addPost');
Route::post('/edit-post', 'ApiController@editPost');

Route::post('/add-reply', 'ApiController@addReply');
Route::post('/edit-reply', 'ApiController@editReply');

Route::post('/add-reply-of-reply', 'ApiController@addReplyofReply');
Route::post('/edit-reply-of-reply', 'ApiController@editReplyofReply');

// Route::get('/send-message', 'ApiController@sendMessage');
Route::post('/send-messages', 'ApiController@sendMessage');
Route::post('/send-email-company', 'ApiController@sendMail');

Route::post('/my-messages', 'ApiController@myMessages');

Route::post('/area-expertise-list', 'ApiController@getareaofexpertise');
Route::post('/get-company-types ', 'ApiController@getcompanytypes');

Route::post('/get-all-messages', 'ApiController@getMessage');
