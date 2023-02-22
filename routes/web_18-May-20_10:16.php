<?php
Route::get('/admin', function() {
	return redirect()->route('dashboard_login');
} );
Route::group(['prefix' => 'admin'], function () {

	Route::group(['middleware' => 'IfAdminNotLogIn'], function() {
		
		Route::get('/login', 'DashboardController@login')->name('dashboard_login');
		Route::post('/login', 'DashboardController@loginAction')->name('dashboard_login_action');
		Route::get('/password-reset-link', 'DashboardController@resetLink')->name('reset_link');
		Route::post('/send-reset-link', 'DashboardController@sendLink')->name('send_link');
		Route::get('/reset-password/{token}', 'DashboardController@resetPassword')->name('reset_pwd');
		Route::post('/reset-password/{token}', 'DashboardController@resetPasswordAction')->name('post_reset_pwd');

	}); // end IfAdminNotLogIn middleware


	/********** AFTER ADMIN LOGIN PART **********/
	/********** DASHBOARD ACTION START *********/
	Route::group(['prefix' => 'dashboard',  'middleware' => ['IfAdminLogIn'] ], function () {
		
		Route::get('/', 'DashboardController@index')->name('dashboard');
		Route::get('/logout', 'DashboardController@logout')->name('logout');

		Route::get('/arindam/module', 'ArindamController@module');
		Route::post('/arindam/module', 'ArindamController@moduleSave')->name('ari.module.save');
		Route::get('/arindam/permission', 'ArindamController@permission');
		Route::post('/arindam/permission', 'ArindamController@permissionSave')->name('ari.permission.save');

		Route::group(['prefix' => 'settings'], function () {  //, 'middleware' => ['role:Super-Admin'] 

			Route::get('/', 'SettingsController@generalSettings')->name('gen_sett');
			Route::post('/save', 'SettingsController@saveGeneralSettings')->name('sv_gen_sett');
		});

		/*************** USER MANAGEMENT ******************/
		Route::group(['prefix' => 'users-management'], function () {

			// Route::get('/', 'UserController@index')->name('users_list')->middleware('permission:admin-view');
			// Route::get('/create-user', 'UserController@createUser')->name('crte_user')->middleware('permission:admin-create');
			// Route::post('/save-user', 'UserController@saveUser')->name('save_user');
			// Route::get('/edit-user/{user_timestamp_id}', 'UserController@editUser')->name('edit_user')->middleware('permission:admin-edit');
			// Route::post('/update-user/{user_timestamp_id}', 'UserController@updateUser')->name('upd_user');
			// Route::get('/reset-password/{user_timestamp_id}', 'UserController@resetPassword')->name('rst_pwd')->middleware('permission:admin-password-reset');




			Route::get('/', 'UserController@index')->name('users_list');
			Route::get('/create-user', 'UserController@createUser')->name('crte_user');
			Route::post('/save-user', 'UserController@saveUser')->name('save_user');
			Route::get('/edit-user/{user_timestamp_id}', 'UserController@editUser')->name('edit_user');
			Route::post('/update-user/{user_timestamp_id}', 'UserController@updateUser')->name('upd_user');
			Route::get('/reset-password/{user_timestamp_id}', 'UserController@resetPassword')->name('rst_pwd');


			Route::post('/update-password/{user_timestamp_id}', 'UserController@updatePassword')->name('upd_pwd');
			Route::get('/delete-user/{user_timestamp_id}', 'UserController@deleteUser')->name('del_usr');
			Route::get('/profile', 'UserController@profile')->name('usr_profile');
			Route::post('/profile', 'UserController@profileUpdate')->name('upd_profile');
			Route::get('/change-password', 'UserController@changePassword')->name('cng_pwd');
			Route::post('/change-password', 'UserController@changePasswordSave')->name('save_pwd');
			Route::post('/users-action', 'UserController@takeAction')->name('usrTKact');

			//Route::get('/permissions/{user_timestamp_id}', 'UserController@userPermissions')->name('edit_user_permission')->middleware('permission:manage-permissions');
			//Route::post('/permissions/{user_timestamp_id}', 'UserController@userPermissionsUpdate')->name('update_user_permission');
			
		});


		Route::group(['prefix' => 'industryCategory'], function() {
				

				Route::get('/categories', 'IndustryController@all_industryCats')->name('allIndustryCats');
				Route::get('/create-category', 'IndustryController@create_industryCats')->name('addIndustryCats');
				Route::post('/save-category', 'IndustryController@save_industryCats')->name('sveIndustryCats');
				Route::get('/delete-category/{id}', 'IndustryController@del_industryCats')->name('delIndustryCats');
				Route::get('/edit-category/{id}', 'IndustryController@edit_industryCats')->name('edtIndustryCats');
				Route::post('/update-category/{id}', 'IndustryController@update_industryCats')->name('updIndustryCats');
			});

		/*************** SOCILA LINKS ******************/
		Route::group(['prefix' => 'social-links'], function() {

			Route::get('/', 'SettingsController@socialLinksList')->name('social_links');
			Route::get('/add', 'SettingsController@addSocialLink')->name('add_social_link');
			Route::post('/add', 'SettingsController@saveSocialLink')->name('sve_social_link');
			Route::get('/edit/{id}', 'SettingsController@addSocialLink')->name('edit_social_link');
			Route::post('/edit/{id}', 'SettingsController@updateSocialLink')->name('upd_social_link');
			Route::get('/delete/{id}', 'SettingsController@deleteSocialLink')->name('del_social_link');
		});

		/*************** ANALYTICAL SCRIPTS ******************/
		Route::group(['prefix' => 'analytic-scripts' ], function() { //'middleware' => ['permission:access analytic-scripts']
			
			Route::get('/', 'SettingsController@anaLyticScripts')->name('anaLyticScripts');
			Route::get('/ajax-layout', 'SettingsController@getAjaxLayout')->name('ajax_layout');
			Route::post('/save-script', 'SettingsController@saveScript')->name('save_script');
			Route::post('/delete-script', 'SettingsController@deleteScript')->name('delete_script');
		});

		/*************** EMAIL TEMPLATES ******************/
		Route::group(['prefix' => 'email-templates'], function() {

			Route::get('/', 'EmailTemplateController@index')->name('empTemp_lists');
			Route::get('/add', 'EmailTemplateController@addEmTemplate')->name('add_empTemp');
			Route::post('/save', 'EmailTemplateController@saveEmTemplate')->name('save_empTemp');
			Route::get('/edit/{id}', 'EmailTemplateController@editEmTemplate')->name('edit_empTemp');
			Route::post('/update/{id}', 'EmailTemplateController@updateEmTemplate')->name('update_empTemp');
			Route::get('/delete/{id}', 'EmailTemplateController@deleteEmTemplate')->name('delete_empTemp');
			Route::get('/settings', 'EmailTemplateController@settings')->name('emp_sett');
			Route::post('/settings', 'EmailTemplateController@settingsSave')->name('emp_sett_save');
		});

		/*************** BANNER MANAGEMENT ******************/
		Route::group(['prefix' => 'banner-management'], function() {
			Route::get('/', 'BannerController@index')->name('bannList')->middleware('permission:banner-view');
			Route::get('/add', 'BannerController@add')->name('addBann');
			Route::post('/save', 'BannerController@save')->name('sveBann');
			Route::get('/edit/{id}', 'BannerController@edit')->name('editBann');
			Route::post('/update/{id}', 'BannerController@update')->name('updateBann');
			Route::get('/delete/{id}', 'BannerController@delete')->name('delBann');
			Route::post('/bulk-action', 'BannerController@bulkAction')->name('Bann.blkAct');
		});

		/*************** CONTENT MANAGEMENT ******************/
		Route::group(['prefix' => 'contents'], function() {
			
			Route::get('/', 'ContentController@allContentTypes')->name('allContTyps');
			Route::get('/add-type', 'ContentController@addContentType')->name('addContTyp');
			Route::post('/save-type', 'ContentController@saveContentType')->name('sveContTyp');
			Route::get('/edit-type/{id}', 'ContentController@editContentType')->name('edtContTyp');
			Route::post('/update-type/{id}', 'ContentController@updateContentType')->name('updContTyp');

			Route::get('/all-managements', 'ContentController@allManagements')->name('allManagement');
			Route::get('/add-page-management/{content_type_id?}', 'ContentController@addPageManagement')->name('addPageManagement');
			Route::post('/save-page-management', 'ContentController@savePageManagement')->name('savePageManagement');

			Route::get('/all-types', 'ContentController@allTypesList')->name('typeList');
			Route::get('/manage/{type}/{type_id}', 'ContentController@manageList')->name('mngLists');
			Route::get('/add/{type}/{type_id}', 'ContentController@addContent')->name('addDynaCont');
			Route::post('/save/{type}/{type_id}', 'ContentController@saveContent')->name('sveDynaCont');
			Route::get('/edit/{type}/{type_id}/{id}', 'ContentController@editContent')->name('edtDynaCont');
			Route::post('/update/{type}/{type_id}/{id}', 'ContentController@updateContent')->name('updDynaCont');
			Route::get('/delete/{type}/{type_id}/{id}', 'ContentController@deleteContent')->name('delDynaCont');

			Route::group(['prefix' => 'cms'], function() {
				Route::get('/', 'ContentController@allCMS')->name('allCMS');
				Route::get('/add', 'ContentController@addCMS')->name('addCMS');
				Route::post('/save', 'ContentController@saveCMS')->name('saveCMS');
				Route::get('/edit/{id}', 'ContentController@editCMS')->name('editCMS');
				Route::post('/update/{id}', 'ContentController@updateCMS')->name('updateCMS');
			});

			Route::post('/bulk-action', 'ContentController@bulkAction')->name('cont.blkAct');
			Route::get('/delete-type/{id}', 'ContentController@deleteContentType')->name('delContTyp');

		});

		/*************** EVENT ******************/
		Route::group(['prefix' => 'events'], function() {
			Route::get('/', 'EventController@index')->name('event.all');
			Route::get('/add', 'EventController@add')->name('event.add');
			Route::post('/save', 'EventController@save')->name('event.save');
			Route::get('/edit/{id}', 'EventController@edit')->name('event.edit');
			Route::post('/update/{id}', 'EventController@update')->name('event.update');
			Route::get('/delete/{id}', 'EventController@delete')->name('event.delete');
			Route::post('/bulk-action', 'EventController@bulkAction')->name('event.blkAct');
		});

		/*************** PROJECT ******************/
		Route::group(['prefix' => 'projects'], function() {
			Route::get('/', 'ProjectController@index')->name('project.all');
			Route::get('/add', 'ProjectController@add')->name('project.add');
			Route::post('/save', 'ProjectController@save')->name('project.save');
			Route::get('/edit/{id}', 'ProjectController@edit')->name('project.edit');
			Route::post('/update/{id}', 'ProjectController@update')->name('project.update');
			Route::get('/delete/{id}', 'ProjectController@delete')->name('project.delete');
			Route::post('/bulk-action', 'ProjectController@bulkAction')->name('project.blkAct');
		});

		/*************** CAREER ******************/
		Route::group(['prefix' => 'careers'], function() {
			Route::get('/', 'CareerController@index')->name('career.all');
			Route::get('/add', 'CareerController@add')->name('career.add');
			Route::post('/save', 'CareerController@save')->name('career.save');
			Route::get('/edit/{id}', 'CareerController@edit')->name('career.edit');
			Route::post('/update/{id}', 'CareerController@update')->name('career.update');
			Route::get('/delete/{id}', 'CareerController@delete')->name('career.delete');
			Route::post('/bulk-action', 'CareerController@bulkAction')->name('career.blkAct');
		});
                
                
                /*************** CONTACT ******************/
		Route::group(['prefix' => 'contact'], function() {
			Route::get('/', 'ContactController@index')->name('contact.all');
			Route::get('/add', 'ContactController@add')->name('contact.add');
			Route::post('/save', 'ContactController@save')->name('contact.save');
			Route::get('/edit/{id}', 'ContactController@edit')->name('contact.edit');
			Route::post('/update/{id}', 'ContactController@update')->name('contact.update');
			Route::get('/delete/{id}', 'ContactController@delete')->name('contact.delete');
			Route::post('/bulk-action', 'ContactController@bulkAction')->name('contact.blkAct');
		});
                
                /*************** OFFICE CONTACT ******************/
		Route::group(['prefix' => 'office_contact'], function() {
			Route::get('/', 'OfficeContactController@index')->name('office_contact.all');
			Route::get('/edit/{id}', 'OfficeContactController@edit')->name('office_contact.edit');
			Route::post('/update/{id}', 'OfficeContactController@update')->name('office_contact.update');

		});

		/*************** CAREER ******************/
		Route::group(['prefix' => 'popups'], function() {
			Route::get('/', 'PopupController@index')->name('popup.all');
			Route::get('/add', 'PopupController@add')->name('popup.add');
			Route::post('/save', 'PopupController@save')->name('popup.save');
			Route::get('/edit/{id}', 'PopupController@edit')->name('popup.edit');
			Route::post('/update/{id}', 'PopupController@update')->name('popup.update');
			Route::get('/delete/{id}', 'PopupController@delete')->name('popup.delete');
		});

		/*************** MENU ******************/
		Route::group(['prefix' => 'menu'], function() {
			Route::get('/', 'MenuController@index')->name('menu.all');
			Route::post('/save', 'MenuController@save')->name('menu.save');
			Route::get('/delete/{id}', 'MenuController@delete')->name('menu.delete');
			Route::post('/bulk-action', 'MenuController@bulkAction')->name('menu.blkAct');
			Route::get('/{menu_id}/submenus/', 'MenuController@subMenuList')->name('submenu.list');
			Route::get('/{menu_id}/submenus/add', 'MenuController@addSubMenu')->name('submenu.add');
			Route::post('/{menu_id}/submenus/save', 'MenuController@saveSubMenu')->name('submenu.save');
			Route::get('/{menu_id}/submenus/edit/{id}', 'MenuController@editSubMenu')->name('submenu.edit');
			Route::post('/{menu_id}/submenus/update/{id}', 'MenuController@updateSubMenu')->name('submenu.update');
			Route::get('/{menu_id}/submenus/delete/{id}', 'MenuController@deleteSubMenu')->name('submenu.delete');
			Route::post('/{menu_id}/submenus/bulk-action', 'MenuController@bulkActionSubmenu')->name('submenu.blkAct');
		});

		/*************** ENQUIRY ******************/
		Route::group(['prefix' => 'enquiries'], function () {

			Route::get('/', 'EnquiryController@allEnquiries')->name('enquiry.data');
			Route::get('/details/{id}', 'EnquiryController@enquiryDetails')->name('enquiry.details');
			Route::get('/delete/{id}', 'EnquiryController@enquiryDelete')->name('enquiry.delete');
			Route::get('/export/{type}', 'EnquiryController@exportData')->name('enquiry.export');							
		});
		
		/*************** MEDIA MANAGEMENT ******************/
		Route::group(['prefix' => 'media-library'], function() {

			Route::group(['prefix' => 'images'], function() {
				Route::get('/', 'MediaController@all_images')->name('media_all_imgs');
				Route::get('/add', 'MediaController@add')->name('media_img_add');
				Route::post('/add', 'MediaController@upload')->name('media_img_upload');
				Route::get('/details/{id}', 'MediaController@imgDetails')->name('media_img_detl');
				Route::post('/details/{id}', 'MediaController@imgDetailsUpdate')->name('media_img_Upd');
				Route::get('/delete/{id}', 'MediaController@imgDelete')->name('media_img_del');
				Route::post('/multi-delete', 'MediaController@imgMultiDelete')->name('media_img_multidel');
				Route::post('/ajax-media-img-delete', 'MediaController@ajaxImgDelete')->name('media_img_ajxDel');

				Route::get('/categories', 'MediaController@img_categories')->name('media_all_img_cats');
				Route::get('/categories/create', 'MediaController@imgCat_Create')->name('media_img_cats_crte');
				Route::post('/categories/create', 'MediaController@imgCat_Save')->name('media_img_cats_save');
				Route::get('/categories/edit/{id}', 'MediaController@imgCat_Edit')->name('media_img_cats_edt');
				Route::post('/categories/edit/{id}', 'MediaController@imgCat_Update')->name('media_img_cats_upd');
				Route::get('/categories/delete/{id}', 'MediaController@imgCat_Delete')->name('media_img_cats_del');
				Route::get('/categories/add-image/{id}', 'MediaController@img_categories_addImg')->name('media_all_img_cats_addImg');
				Route::post('/categories/add-image/{id}', 'MediaController@img_categories_upImg')->name('media_all_img_cats_upImg');

			});


			Route::group(['prefix' => 'videos'], function() {
				Route::get('/', 'MediaController@all_videos')->name('allVideos');
				Route::get('/add', 'MediaController@add_video')->name('addVideo');
				Route::post('/save', 'MediaController@save_video')->name('saveVideo');
				Route::get('/delete/{id}', 'MediaController@del_video')->name('delVideo');
				Route::get('/edit/{id}', 'MediaController@edit_video')->name('editVideo');
				Route::post('/edit/{id}', 'MediaController@update_video')->name('updVideo');

				Route::get('/categories', 'MediaController@all_videoCats')->name('videoCats');
				Route::get('/create-category', 'MediaController@add_videoCats')->name('addVideoCats');
				Route::post('/save-category', 'MediaController@save_videoCats')->name('sveVideoCats');
				Route::get('/delete-category/{id}', 'MediaController@del_videoCats')->name('delVideoCats');
				Route::get('/edit-category/{id}', 'MediaController@edit_videoCats')->name('edtVideoCats');
				Route::post('/update-category/{id}', 'MediaController@update_videoCats')->name('updVideoCats');
			});


			Route::group(['prefix' => 'files'], function() {
				Route::get('/', 'MediaController@all_files')->name('allFiles');
				Route::get('/add', 'MediaController@add_file')->name('addFile');
				Route::post('/upload', 'MediaController@upload_file')->name('uploadFile');
				Route::get('/delete/{id}', 'MediaController@delete_file')->name('delFile');
				Route::get('/edit/{id}', 'MediaController@edit_file')->name('edtFile');
				Route::post('/update/{id}', 'MediaController@update_file')->name('updFile');
				Route::post('/multi-delete', 'MediaController@fileMultiDelete')->name('media_file_multidel');

				Route::get('/categories', 'MediaController@all_flCats')->name('allFlCats');
				Route::get('/create-category', 'MediaController@create_flCat')->name('crteFlCat');
				Route::post('/save-category', 'MediaController@save_flCat')->name('saveFlCat');
				Route::get('/delete-category/{id}', 'MediaController@delete_flCat')->name('delFlCat');
				Route::get('/edit-category/{id}', 'MediaController@edit_flCat')->name('editFlCat');
				Route::post('/update-category/{id}', 'MediaController@update_flCat')->name('updFlCat');

				Route::get('/file-data-delete', 'MediaController@fileDataDelete')->name('flDD');
			});

		});

		Route::group(['prefix' => 'links'], function() {
			Route::get('/', 'FooterController@all_links')->name('allLinks');
			Route::get('/add', 'FooterController@add_link')->name('addLink');
			Route::post('/save', 'FooterController@save_link')->name('saveLink');
			Route::get('/delete/{id}', 'FooterController@delete_link')->name('delLink');
			Route::get('/edit/{id}', 'FooterController@edit_link')->name('edtLink');
			Route::post('/update/{id}', 'FooterController@update_link')->name('updLink');
			Route::post('/bulk-action', 'FooterController@bulkAction')->name('link.blkAct');			
		});
		


		/*************** File MANAGEMENT ******************/
		Route::group(['prefix' => 'file-library'], function() {

			Route::group(['prefix' => 'images'], function() {
				Route::get('/', 'FileController@all_images')->name('file_all_imgs');
				Route::get('/add', 'FileController@add')->name('file_img_add');
				Route::post('/add', 'FileController@upload')->name('file_img_upload');
				Route::get('/details/{id}', 'FileController@imgDetails')->name('file_img_detl');
				Route::post('/details/{id}', 'FileController@imgDetailsUpdate')->name('file_img_Upd');
				Route::get('/delete/{id}', 'FileController@imgDelete')->name('file_img_del');
				Route::post('/multi-delete', 'FileController@imgMultiDelete')->name('file_img_multidel');
				Route::post('/ajax-file-img-delete', 'FileController@ajaxImgDelete')->name('file_img_ajxDel');

				Route::get('/categories', 'FileController@img_categories')->name('file_all_img_cats');
				Route::get('/categories/create', 'FileController@imgCat_Create')->name('file_img_cats_crte');
				Route::post('/categories/create', 'FileController@imgCat_Save')->name('file_img_cats_save');
				Route::get('/categories/edit/{id}', 'FileController@imgCat_Edit')->name('file_img_cats_edt');
				Route::post('/categories/edit/{id}', 'FileController@imgCat_Update')->name('file_img_cats_upd');
				Route::get('/categories/delete/{id}', 'FileController@imgCat_Delete')->name('file_img_cats_del');
				Route::get('/categories/add-image/{id}', 'FileController@img_categories_addImg')->name('file_all_img_cats_addImg');
				Route::post('/categories/add-image/{id}', 'FileController@img_categories_upImg')->name('file_all_img_cats_upImg');

			});


			Route::group(['prefix' => 'videos'], function() {
				Route::get('/', 'FileController@all_videos')->name('allVideosFile');
				Route::get('/add', 'FileController@add_video')->name('addVideoFile');
				Route::post('/save', 'FileController@save_video')->name('saveVideoFile');
				Route::get('/delete/{id}', 'FileController@del_video')->name('delVideoFile');
				Route::get('/edit/{id}', 'FileController@edit_video')->name('editVideoFile');
				Route::post('/edit/{id}', 'FileController@update_video')->name('updVideoFile');

				Route::get('/categories', 'FileController@all_videoCats')->name('videoCatsFile');
				Route::get('/create-category', 'FileController@add_videoCats')->name('addVideoCatsFile');
				Route::post('/save-category', 'FileController@save_videoCats')->name('sveVideoCatsFile');
				Route::get('/delete-category/{id}', 'FileController@del_videoCats')->name('delVideoCatsFile');
				Route::get('/edit-category/{id}', 'FileController@edit_videoCats')->name('edtVideoCatsFile');
				Route::post('/update-category/{id}', 'FileController@update_videoCats')->name('updVideoCatsFile');
			});

		});

		Route::group(['prefix' => 'faq'], function() {
			Route::get('/', 'FileController@all_faqs')->name('allFaqsFile');
			Route::get('/add', 'FileController@add_faq')->name('addFaqFile');
			Route::post('/save', 'FileController@save_faq')->name('saveFaqFile');
			Route::get('/delete/{id}', 'FileController@del_faq')->name('delFaqFile');
			Route::get('/edit/{id}', 'FileController@edit_faq')->name('editFaqFile');
			Route::post('/edit/{id}', 'FileController@update_faq')->name('updFaqFile');

			Route::get('/categories', 'FileController@all_faqCats')->name('faqCatsFile');
			Route::get('/create-category', 'FileController@add_faqCats')->name('addFaqCatsFile');
			Route::post('/save-category', 'FileController@save_faqCats')->name('sveFaqCatsFile');
			Route::get('/delete-category/{id}', 'FileController@del_faqCats')->name('delFaqCatsFile');
			Route::get('/edit-category/{id}', 'FileController@edit_faqCats')->name('edtFaqCatsFile');
			Route::post('/update-category/{id}', 'FileController@update_faqCats')->name('updFaqCatsFile');
		});

		

		/*************** AJAX ******************/
		Route::group(['prefix' => 'ajax'], function() {
			Route::post('/GalleryImageUpload', 'MediaController@ajaxGalImgUpload')->name('ajaxGalImgUpload');
			Route::post('/checkSlugUrlSelf', 'AjaxController@checkSlugUrlSelf')->name('checkSlugUrlSelf');
			Route::post('/ajax-file-delete', 'AjaxController@fileDelete')->name('ajxFileDel');
			Route::post('/ajax-media-images-upload', 'AjaxElementController@mediaImageUpload')->name('ajxMediaImgUpload');
			Route::get('/ajax-image-library', 'AjaxElementController@mediaImageLibrary')->name('ajxMediaImgLibrary');
			Route::get('/ajax-load-galleries', 'AjaxElementController@mediaLoadImageGalleries')->name('ajxMediaLdImgGals');
			Route::post('/ajax-elemodal-scodes', 'AjaxElementController@eleShortCodes')->name('ajxEleScodes');
			Route::post('/ajax-media-files-upload', 'AjaxElementController@mediaFileUpload')->name('ajxMediaFileUpload');
			Route::get('/ajax-get-media-files', 'AjaxElementController@getMediaFiles')->name('ajxMediaFileLibrary');
			Route::get('/ajax-load-file-categories', 'AjaxElementController@mediaLoadFileCategories')->name('ajxMediaLdFlCats');
			Route::post('/ajax-load-file-subcategories', 'AjaxElementController@mediaLoadFileSubCategories')->name('ajxMediaLdFlSCats');
			Route::post('/ajax-load-file-subcategories_byslug', 'AjaxElementController@mediaLoadFileSubCategoriesSlug')->name('ajxMediaLdFlSCatsSlug');
			Route::post('/ajax-load-image-subcategories', 'AjaxElementController@mediaLoadImageSubCategories')->name('ajxMediaLdImgSCats');

			Route::get('/ajax-load-image-categories', 'AjaxElementController@mediaLoadImgCategories')->name('ajxMediaLdImgCats');
			Route::post('/ajax-load-image-subcategories_byslug', 'AjaxElementController@mediaLoadImgSubCategoriesSlug')->name('ajxMediaLdImgSCatsSlug');

			Route::post('/ajax-load-video-subcategories', 'AjaxElementController@mediaLoadVidSubCategories')->name('ajxMediaLdVdSCats');

			Route::post('/ajax-media-video-add', 'AjaxElementController@mediaVideoAdd')->name('ajxMediaVidAdd');
			Route::get('/ajax-video-library', 'AjaxElementController@mediaVideoLibrary')->name('ajxMediaVidLibrary');
			
			Route::post('/ajax-answer-delete', 'AjaxController@ajaxAnswerDelete')->name('ajaxAnswerDelete');

		});

		Route::get('/active-inactive', 'CommonController@activeInactive')->name('acInac');
		Route::get('/forced-email-verify/{uid}', 'CommonController@forcedEmailVerify')->name('forceEmailVerify');
		/*** Global Image Delete ***/
		Route::get('/global-image-delete', 'DashboardController@globalImageDelete')->name('glbImgDel');

	}); //end dashboard prefix
	/********** END DASHBOARD ACTION *********/

}); //end admin prefix


