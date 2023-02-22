<?php

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

		Route::group(['prefix' => 'settings'], function () {  //, 'middleware' => ['role:Super-Admin'] 

			Route::get('/', 'SettingsController@generalSettings')->name('gen_sett');
			Route::post('/save', 'SettingsController@saveGeneralSettings')->name('sv_gen_sett');
		});

		/*************** USER MANAGEMENT ******************/
		Route::group(['prefix' => 'users-management'], function () {

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

			
			Route::get('/system-permissions', 'UserController@systemPermissions')->name('sysPerm');
			Route::post('/add-system-permissions', 'UserController@systemPermissions_Add')->name('sysPerm_Add');
			Route::get('/edit-system-permissions/{id}', 'UserController@systemPermissions_Edit')->name('sysPerm_Edt');
			Route::post('/update-system-permissions/{id}', 'UserController@systemPermissions_Update')->name('sysPerm_Upd');
			Route::get('/delete-system-permissions/{id}', 'UserController@systemPermissions_Delete')->name('sysPerm_Del');

			Route::get('/roles', 'UserController@allRoles')->name('allRoles');
			Route::get('/create-role', 'UserController@createRole')->name('crtRole');
			Route::post('/add-role', 'UserController@addRole')->name('addRole');
			Route::get('/manage-role-permissions/{role_id}', 'UserController@manageRolePermission')->name('mngRolePerm');
			Route::post('/update-role/{role_id}', 'UserController@updateRole')->name('updRole');
			Route::get('/delete-role/{role_id}', 'UserController@deleteRole')->name('delRole');

			Route::get('/role-permissions/{role_id}', 'UserController@showRolePermissions')->name('shw.role_Perm');
			Route::get('/delete-permission-from-role/{role_id}/{perm_id}', 'UserController@deletePermFromRole')->name('del.permFrole');

			Route::get('/user-permissions/{user_id}', 'UserController@userPermissions')->name('usr.Perm');
			Route::get('/user-manage-direct-permission', 'UserController@userDirectPermission')->name('usr.dirt.perm');

			Route::get('/roles-manage-permissions/{role_id}', 'UserController@roleManagePermissions')->name('rlMnPer');
			Route::post('/save-roles-permissions/{role_id}', 'UserController@saveRolePermissions')->name('sveRlPerm');

			Route::post('/users-action', 'UserController@takeAction')->name('usrTKact');
			
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
			Route::get('/', 'BannerController@index')->name('bannList');
			Route::get('/add', 'BannerController@add')->name('addBann');
			Route::post('/save', 'BannerController@save')->name('sveBann');
			Route::get('/edit/{id}', 'BannerController@edit')->name('editBann');
			Route::post('/update/{id}', 'BannerController@update')->name('updateBann');
			Route::get('/delete/{id}', 'BannerController@delete')->name('delBann');
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


