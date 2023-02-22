<?php
Route::get('/', 'FrontendController@index')->name('front.index');
Route::get('/contact-us', 'FrontendController@contactUs')->name('front.contactus');
Route::post('/contact-us/insert', 'FrontendController@insert')->name('contactus.insert');
Route::get('/image-gallery', 'FrontendController@imageGallery')->name('front.imgGal');
Route::get('/image-gallery/images/{id}', 'FrontendController@images')->name('front.galImgs');


Route::get('/video-gallery', 'FrontendController@videoGallery')->name('front.vidGal');
Route::get('/image-gallery/video/{id}', 'FrontendController@video')->name('front.galVdo');


Route::get('/faqs', 'FrontendController@faqs')->name('front.faq');


/* Don't delete this */
Route::get('contents/{slug}', 'FrontendController@cmsPages')->name('cmsPages');
Route::get('events/{slug}', 'FrontendController@evtPages')->name('evtPages');
Route::get('projects/{slug}', 'FrontendController@projPages')->name('projPages');
Route::get('careers/{slug}', 'FrontendController@carrPages')->name('carrPages');


