<?php
Route::group(['middleware' => 'IfUserNotLogIn'], function() {
    Route::get('/', 'FrontendController@index')->name('signinup');
    Route::get('/email-signup', 'FrontendController@signup')->name('email.signinup');
    

    Route::get('/signup', 'FrontendController@signupIndex');
    Route::get('/signin', 'FrontendController@signinIndex');
    Route::post('/signup', 'FrontendController@userSignup')->name('front.user.signup');
    Route::post('/signin', 'FrontendController@userLogin')->name('front.user.login');
});

Route::group(['middleware' => 'IfUserLogIn'], function() {
    Route::get('/account', 'FrontendController@account')->name('front.user.account');

    Route::get('/feeds', 'FrontendController@feed')->name('front.user.feed');

    Route::get('/startup', 'FrontendController@startup')->name('front.user.startup');
    Route::get('/company', 'FrontendController@company')->name('front.user.company');

    Route::get('/bookmark', 'FrontendController@bookmark')->name('front.user.bookmark');

    Route::get('/dashboard', 'FrontendController@dasboard')->name('front.user.dashboard');
    Route::get('/last-login_post', 'FrontendController@accountLast')->name('front.user.last-login_post');
    Route::get('/logout', 'FrontendController@logout')->name('front.user.logout');
    Route::post('/saveMyPost', 'FrontendController@saveMyPost')->name('front.user.savepost');
    Route::get('/autocomplete', 'FrontendController@autoComplete')->name('front.user.autoComplete');
    Route::get('/change-password', 'FrontendController@changePassword')->name('front.user.cngpwd');
    Route::post('/change-password', 'FrontendController@updatePassword')->name('front.user.updpwd');
    Route::get('/manage-profile', 'FrontendController@manageProfile')->name('front.user.mngprof');
    Route::get('/manage-user-profile', 'FrontendController@manageUserProfile')->name('front.user.mnguserprof');
    
    Route::post('/manage-profile', 'FrontendController@updateProfile')->name('front.user.updprof');

    Route::get('/add_user', 'FrontendController@addUser')->name('front.user.addusr');

    Route::post('/add-user-action', 'FrontendController@addUserAction')->name('front.user.addusract');
    Route::post('/update-user-action', 'FrontendController@updateUserAction')->name('front.user.uptusract');
    

 
    
    Route::get('/my-posts', 'FrontendController@myPosts')->name('front.user.myposts');

    Route::get('/myfav-posts', 'FrontendController@myFavPosts')->name('front.user.myfavposts');

    Route::get('/edit-post/{post_id}', 'FrontendController@editPost')->name('front.user.editpost');
    Route::post('/edit-post/{post_id}', 'FrontendController@updatePost')->name('front.user.updatepost');
    Route::post('/add-comment', 'FrontendController@ajaxAddComment')->name('front.user.addComment');

    Route::post('/add-favourite', 'FrontendController@ajaxAddFavorate')->name('front.user.addFavourate');
    Route::post('/send-mail', 'FrontendController@sendMail')->name('front.user.sendMail');
    Route::get('/member-profile/{timestamp_id}', 'FrontendController@memberProfile')->name('front.user.memberProfile');
    Route::get('/send-message', 'FrontendController@sendMessage')->name('front.user.sendMessage');
    Route::post('/send-messages', 'FrontendController@sendMessages')->name('front.user.sendMessages');
    Route::get('/my-messages', 'FrontendController@myMessages')->name('front.user.myMessages');
     
});
Route::get('/export-all', 'FrontendController@export_all');
Route::get('/export-categories', 'FrontendController@exportCaterories');

