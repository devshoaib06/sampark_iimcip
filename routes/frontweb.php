<?php
Route::group(['middleware' => 'IfUserNotLogIn'], function () {
	Route::get('/', 'FrontendController@index')->name('signinup');
	Route::get('/email-signup', 'FrontendController@signup')->name('email.signinup');
	Route::get('/forget-password', 'FrontendController@forgetPassword')->name('front.user.password');

	Route::get('/signup', 'FrontendController@signupIndex');
	Route::get('/signin', 'FrontendController@signinIndex');
	Route::post('/signup', 'FrontendController@userSignup')->name('front.user.signup');

	Route::post('/reset-password', 'FrontendController@sendLink')->name('front.reser.password');
	Route::post('/signin', 'FrontendController@userLogin')->name('front.user.login');
});

Route::group(['middleware' => 'IfUserLogIn'], function () {
	Route::get('/account', 'FrontendController@account')->name('front.user.account');

	Route::get('/allcheckposts', 'FrontendController@allcheckpost')->name('front.user.allcheckpost');
	Route::get('/notification', 'FrontendController@notification')->name('front.user.notification');

	Route::get('/feeds', 'FrontendController@feed')->name('front.user.feed');

	Route::get('/startup', 'FrontendController@startup')->name('front.user.startup');

	Route::get('/company', 'FrontendController@company')->name('front.user.company');

	Route::get('mentor/startup', 'FrontendController@mentorStartup')->name('front.mentor.startup');

	Route::get('mentor/incubereports', 'FrontendController@mentorIncubeReports')->name('front.mentor.incubereports');

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

	Route::get('/public-profile', 'FrontendController@publicProfile')->name('front.user.pblprof');

	Route::get('/private-profile', 'FrontendController@privateProfile')->name('front.user.pvtprof');


	Route::get('/add_customer', 'FrontendController@addCustomer')->name('front.user.addcust');
	Route::post('/add-customer-action', 'FrontendController@addCustomerAction')->name('front.user.addcustact');
	Route::get('/customer/edit/{id}', 'FrontendController@custEdit')->name('customer.edit');
	Route::post('/customer/update/{id}', 'FrontendController@custUpdate')->name('customer.update'); 
	Route::get('/customer/destroy/{id}', 'FrontendController@custDestroy')->name('customer.destroy');


	Route::get('/add_financials', 'FrontendController@addFinancial')->name('front.user.addfin');
	Route::post('/add-financials-action', 'FrontendController@addFinancialAction')->name('front.user.addfinact');
	Route::get('/financials/edit/{id}', 'FrontendController@financialEdit')->name('finyear.edit');
	Route::post('/financials/update/{id}', 'FrontendController@financialUpdate')->name('finyear.update'); 
	Route::get('/financials/destroy/{id}', 'FrontendController@financialDestroy')->name('finyear.destroy');




	

	Route::get('/add_financials_month', 'FrontendController@addFinancialMonth')->name('front.user.addfinmon');
	Route::post('/add-financials-month-action', 'FrontendController@addFinancialMonthAction')->name('front.user.addfinmonact');
	Route::get('/financialsmonth/edit/{id}', 'FrontendController@financialMonthEdit')->name('finmonth.edit');
	Route::post('/financialsmonth/update/{id}', 'FrontendController@financialMonthUpdate')->name('finmonth.update'); 
	Route::get('/financialsmonth/destroy/{id}', 'FrontendController@financialMonthDestroy')->name('finmonth.destroy');




	Route::get('/add_financials_expenses', 'FrontendController@addFinancialExpenses')->name('front.user.addfinexp');
	Route::post('/add-financials-expenses-action', 'FrontendController@addFinancialExpenseAction')->name('front.user.addfinexpact');
	Route::get('/financialsexpenses/edit/{id}', 'FrontendController@financialExpenseEdit')->name('finexp.edit');
	Route::post('/financialsexpenses/update/{id}', 'FrontendController@financialExpenseUpdate')->name('finexp.update'); 
	Route::get('/financialsexpenses/destroy/{id}', 'FrontendController@financialExpenseDestroy')->name('finexp.destroy');



	Route::get('/add_order_pipeline', 'FrontendController@addOrderPipeline')->name('front.user.addorderpipe');
	Route::post('/add-monthly-order-pipeline', 'FrontendController@addOrderPipelineAction')->name('front.user.addorderpipeact');
	Route::get('/orderpipeline/edit/{id}', 'FrontendController@OrderPipelineEdit')->name('orderpipe.edit');
	Route::post('/orderpipeline/update/{id}', 'FrontendController@OrderPipelineUpdate')->name('orderpipe.update'); 
	Route::get('/orderpipeline/destroy/{id}', 'FrontendController@OrderPipelineDestroy')->name('orderpipe.destroy');






	Route::get('/add_yearly_targets', 'FrontendController@addYearlyTarget')->name('front.user.addyearlytarget');
	Route::post('/add-yearly-targets-action', 'FrontendController@addYearlyTargetAction')->name('front.user.addyearlytargetact');
	Route::get('/yearlytargets/edit/{id}', 'FrontendController@yearlyTargetEdit')->name('yearly.targets.edit');
	Route::post('/yearlytargets/update/{id}', 'FrontendController@yearlyTargetUpdate')->name('yearly.targets.update'); 
	Route::get('/yearlytargets/destroy/{id}', 'FrontendController@yearlyTargetDestroy')->name('yearly.targets.destroy');




	Route::get('/add_impacts', 'FrontendController@addImpacts')->name('front.user.addimpact');
	Route::post('/add-impacts-action', 'FrontendController@addImpactsAction')->name('front.user.addimpactact');
	Route::get('/impacts/edit/{id}', 'FrontendController@impactsEdit')->name('impacts.edit');
	Route::post('/impacts/update/{id}', 'FrontendController@impactsUpdate')->name('impacts.update'); 
	Route::get('/impacts/destroy/{id}', 'FrontendController@impactsDestroy')->name('impacts.destroy');




	Route::get('/add_funding_needs', 'FrontendController@addFundingNeeds')->name('front.user.addfunding');
	Route::post('/add-funding-needs-action', 'FrontendController@addFundingNeedsAction')->name('front.user.addfundingact');
	Route::get('/fundingneeds/edit/{id}', 'FrontendController@fundingNeedsEdit')->name('funding.needs.edit');
	Route::post('/fundingneeds/update/{id}', 'FrontendController@fundingNeedsUpdate')->name('funding.needs.update'); 
	Route::get('/fundingneeds/destroy/{id}', 'FrontendController@fundingNeedsDestroy')->name('funding.needs.destroy');




	Route::get('/add_compliance_checks', 'FrontendController@addComplianceCheck')->name('front.user.addcompl');
	Route::post('/add-compliance-checks-action', 'FrontendController@addComplianceCheckAction')->name('front.user.addcomplact');
	Route::get('/compliancechecks/edit/{id}', 'FrontendController@complianceChecksEdit')->name('compliancechecks.edit');
	Route::post('/compliancechecks/update/{id}', 'FrontendController@complianceChecksUpdate')->name('compliancechecks.update'); 
	Route::get('/compliancechecks/destroy/{id}', 'FrontendController@complianceChecksDestroy')->name('compliancechecks.destroy');

	//20-09-2021

	Route::get('/risk', array('as' => 'risk', 'uses' => 'RiskController@index'));
	Route::get('/risk/add_risk', array('as' => 'risk.add_risk', 'uses' => 'RiskController@add_risk'));
	Route::get('/risk/edit_risk', array('as' => 'risk.edit_risk', 'uses' => 'RiskController@edit_risk'));
	Route::post('/risk/save_risk', array('as' => 'risk.save_risk', 'uses' => 'RiskController@save_risk'));






	//appointment
	//Route::get('/appointment/list', array('as' => 'appointment.list', 'uses' => 'AppointmentController@ajax_list'));
	Route::get('/appointment/ajax_list', array('as' => 'appointment.ajax_list', 'uses' => 'AppointmentController@ajax_list'));
	//new test
	Route::get('/appointment/list', array('as' => 'appointment.list', 'uses' => 'AppointmentController@ajax_list_new'));
	Route::get('/appointment/ajax_data_new', array('as' => 'appointment.ajax_data_new', 'uses' => 'AppointmentController@ajax_data_new'));
	Route::get('/appointment/appointment_form_mentor', array('as' => 'appointment.appointment_form_mentor', 'uses' => 'AppointmentController@appointmentFormMentor'));
	Route::get('/appointment/appointment_form_startup', array('as' => 'appointment.appointment_form_startup', 'uses' => 'AppointmentController@appointmentFormStartup'));
	Route::get('/appointment/get_appointment_modal', array('as' => 'appointment.get_appointment_modal', 'uses' => 'AppointmentController@getAppointmentModal'));
	Route::get('/appointment/delete_availability', array('as' => 'appointment.delete_availability', 'uses' => 'AppointmentController@delete_availability'));
	Route::get('/appointment/confirm_availability', array('as' => 'appointment.confirm_availability', 'uses' => 'AppointmentController@confirm_availability'));
	Route::post('/appointment/save_availability', array('as' => 'appointment.save_availability', 'uses' => 'AppointmentController@saveAvailability'));
	Route::post('/appointment/save_edited_availability', array('as' => 'appointment.save_edited_availability', 'uses' => 'AppointmentController@saveEditedAvailability'));
	// new test end
	Route::get('/appointment/ajax_data', array('as' => 'appointment.ajax_data', 'uses' => 'AppointmentController@ajax_data'));
	Route::post('/appointment/appointment_save', array('as' => 'appointment.appointment_save', 'uses' => 'AppointmentController@saveAppointment'));
	Route::post('/appointment/appointment_save_direct', array('as' => 'appointment.appointment_save_direct', 'uses' => 'AppointmentController@appointment_save_direct'));
	Route::post('/appointment/appointment_save_mentor', array('as' => 'appointment.appointment_save_mentor', 'uses' => 'AppointmentController@appointment_save_mentor'));

	Route::post('/appointment/appointment_save_startup', array('as' => 'appointment.appointment_save_startup', 'uses' => 'AppointmentController@appointment_save_startup'));

	Route::get('/investor-appointment-approval/{id}', array('as' => 'investor-appointment-approval', 'uses' => 'AppointmentController@investorAppointmentApproval'));
	Route::get('/startup-appointment-approval/{id}', array('as' => 'startup-appointment-approval', 'uses' => 'AppointmentController@startupAppointmentApproval'));

	Route::any('/view_appointment', array('as' => 'admin.view_appointment', 'uses' => 'AppointmentController@view_appointment'));
	Route::any('/savenote', array('as' => 'admin.savenote', 'uses' => 'AppointmentController@savenote'));

	//report start
	Route::any('/list_report', array('as' => 'list_report', 'uses' => 'ReportController@index_startup'));
	Route::any('/add_report', array('as' => 'add_report', 'uses' => 'ReportController@add_report_startup'));
	Route::any('/edit_report/{id}', array('as' => 'edit_report', 'uses' => 'ReportController@edit_report_startup'));
	Route::post('/save_report', array('as' => 'save_report', 'uses' => 'ReportController@save_report_startup'));
	Route::post('/update_report', array('as' => 'update_report', 'uses' => 'ReportController@update_report_startup'));
	Route::any('/download_report/{id}', array('as' => 'download_report', 'uses' => 'ReportController@download_report_startup'));

	Route::any('/incubate_list_report', array('as' => 'incubate_list_report', 'uses' => 'ReportController@new_index_startup'));
	Route::any('/incubate_add_report', array('as' => 'incubate_add_report', 'uses' => 'ReportController@new_add_report_startup'));
	Route::any('/incubate_edit_report/{id}', array('as' => 'incubate_edit_report', 'uses' => 'ReportController@new_edit_report_startup'));
	Route::post('/incubate_save_report', array('as' => 'incubate_save_report', 'uses' => 'ReportController@new_save_report_startup'));
	Route::post('/incubate_update_report', array('as' => 'incubate_update_report', 'uses' => 'ReportController@new_update_report_startup'));
	Route::any('/incubate_download_report/{id}', array('as' => 'incubate_download_report', 'uses' => 'ReportController@new_download_report_startup'));

	Route::any('/incubate_report_list', array('as' => 'incubate_report_list', 'uses' => 'ReportController@incubate_report_list'));
	Route::any('/incubate_report_add', array('as' => 'incubate_report_add', 'uses' => 'ReportController@incubate_report_add'));
	Route::any('/incubate_report_edit/{id}', array('as' => 'incubate_report_edit', 'uses' => 'ReportController@incubate_report_edit'));
	Route::post('/incubate_report_save', array('as' => 'incubate_report_save', 'uses' => 'ReportController@incubate_report_save'));
	Route::post('/incubate_report_update', array('as' => 'incubate_report_update', 'uses' => 'ReportController@incubate_report_update'));
	Route::any('/incubate_report_download/{id}', array('as' => 'incubate_report_download', 'uses' => 'ReportController@incubate_report_download'));
	//report start end


	// Task Categories 
	Route::get('/add_task_category', array('as' => 'admin.add_task_category', 'uses' => 'TaskCategoriesController@create'));
	Route::post('/store_task_category', array('as' => 'admin.store_task_category', 'uses' => 'TaskCategoriesController@store'));
	//Route::get('/list_categories/{id}', array('as' => 'admin.list_categories', 'uses' => 'CategoriesController@index'));
	Route::any('/list_task_categories', array('as' => 'admin.list_task_categories', 'uses' => 'TaskCategoriesController@index'));
	Route::get('/edit_task_category/{id}', array('as' => 'admin.edit_task_category', 'uses' => 'TaskCategoriesController@edit'));
	Route::post('/update_task_category/{id}', array('as' => 'admin.update_task_category', 'uses' => 'TaskCategoriesController@update'));
	Route::get('/delete_task_category/{id}', array('as' => 'admin.delete_task_category', 'uses' => 'TaskCategoriesController@delete'));
	Route::post('/task_category_status_change', array('as' => 'admin.task_category_status_change', 'uses' => 'TaskCategoriesController@task_category_status_change'));
	// Task Categories 


	Route::get('/add_task/{id}', array('as' => 'admin.add_task', 'uses' => 'TaskController@add_task'));

	Route::get('/task_list/{id}', array('as' => 'admin.task_list', 'uses' => 'TaskController@task_list'));
	Route::any('/update_complete_task', array('as' => 'admin.update_complete_task', 'uses' => 'TaskController@update_complete_task'));

	Route::any('delink_multi_startups', array('as' => 'admin.delink_multi_startups', 'uses' => 'PortfolioController@delink_multi_startups'));
	Route::get('/entrepreneur/answer_form', array('as' => 'entrepreneur.answer_form', 'uses' => 'EntrepreneurController@answer_form'));
	Route::any('/entrepreneur/respond', array('as' => 'entrepreneur.respond', 'uses' => 'EntrepreneurController@respond'));
	Route::get('/entrepreneur/answer_log', array('as' => 'entrepreneur.answer_log', 'uses' => 'EntrepreneurController@answer_log'));
	Route::get('/investor/add_task', array('as' => 'investor.add_task', 'uses' => 'InvestorController@add_task'));
	Route::get('/investor/task_list', array('as' => 'investor.task_list', 'uses' => 'InvestorController@task_list'));
	Route::any('/investor/create_task', array('as' => 'investor.create_task', 'uses' => 'InvestorController@create_task'));
	Route::get('/investor/review_task', array('as' => 'investor.review_task', 'uses' => 'InvestorController@review_task'));
	Route::any('/investor/create_review', array('as' => 'investor.create_review', 'uses' => 'InvestorController@create_review'));


	Route::get('/entrepreneur/review_task', array('as' => 'entrepreneur.review_task', 'uses' => 'EntrepreneurController@review_task'));
	Route::any('/entrepreneur/create_review', array('as' => 'entrepreneur.create_review', 'uses' => 'EntrepreneurController@create_review'));
	Route::any('/extend_task_date', array('as' => 'extend_task_date', 'uses' => 'TaskController@extend_task_date'));
	Route::any('/entrepreneur/create_task', array('as' => 'entrepreneur.create_task', 'uses' => 'EntrepreneurController@create_task'));
	Route::any('/entrepreneur/save_report_data', array('as' => 'entrepreneur.save_report_data', 'uses' => 'EntrepreneurController@save_report_data'));







	Route::get('/task_list/{id}', array('as' => 'task_list', 'uses' => 'TaskController@task_list'));

	Route::get('/add_task/{id}', array('as' => 'add_task', 'uses' => 'TaskController@add_task'));

	Route::any('/create_tasks', array('as' => 'add_tasks', 'uses' => 'TaskController@create_task'));


	Route::get('/mentor/review_task', array('as' => 'mentor.review_task', 'uses' => 'TaskController@review_task'));


	Route::any('/mentor/create_review', array('as' => 'mentor.create_review', 'uses' => 'TaskController@create_review'));


	Route::any('/update_complete_task', array('as' => 'update_complete_task', 'uses' => 'TaskController@update_complete_task'));
});
Route::get('/export-all', 'FrontendController@export_all');
Route::get('/export-categories', 'FrontendController@exportCaterories');


Route::get('/mentor-diagnostic', 'FeedbackController@getParameterList')->name('mentorDiagnostic');
Route::match(['get', 'post'], '/mentor-feedback/{id}', 'FeedbackController@mentorFeedback')->name('mentorFeedback');
Route::get('/mentor-diagnostic-feedback', 'FeedbackController@thankYou')->name('thankYou');
