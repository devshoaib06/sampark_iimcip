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
Route::get('/get-startupstage', 'ApiController@getStartupstage');
Route::any('/login-action', 'ApiController@loginAction');
Route::any('/logout-action', 'ApiController@logoutAction');

Route::post('/register-user', 'ApiController@saveUser');
Route::post('/reset-password/{token}', 'ApiController@resetPasswordAction');
Route::post('/user-details', 'ApiController@userDetl');

Route::post('/update-user/{user_timestamp_id}', 'ApiController@updateUser');