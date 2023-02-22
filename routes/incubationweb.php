<?php
Route::get('/incubationdemo', 'IncubationController@allSite');

Route::group(['prefix' => 'incubation'], function () {

  Route::group(['middleware' => 'IfIncubateeNotLogIn'], function () {
    Route::get('/', 'IncubationController@index')->name('signin');
    Route::post('/signin', 'IncubationController@userLogin')->name('incubate.user.login');
  });

  Route::group(['middleware' => 'IfIncubateeLogIn'], function () {
    Route::get('/incubatee/mentor/startup', 'IncubationController@mentorStartup')->name('incubatee.mentor.startup');
    Route::get('/incubatee/home', 'IncubationController@feed')->name('incubatee.user.feed');
    //Route::get('/incubatee/count', 'IncubationController@userCount');
    Route::get('/incubatee/account', 'IncubationController@account')->name('incubatee.user.account');

    Route::get('/incubatee/logout', 'IncubationController@logout')->name('incubatee.user.logout');

    Route::get('/incubatee/todo', 'IncubationController@todo')->name('incubatee.user.todo');
    Route::get('/incubatee/todo/create', 'IncubationController@todoCreate')->name('incubatee.task.create');
    Route::get('/incubatee/todo/edit/{id}', 'IncubationController@todoEdit')->name('incubatee.task.edit');
    Route::get('/incubatee/todo/view/{id}', 'IncubationController@todoView')->name('incubatee.task.view');
    Route::delete('/incubatee/todo/destroy/{id}', 'IncubationController@todoDestroy')->name('incubatee.task.destroy');
    Route::post('/incubatee/todo/store', 'IncubationController@todoStore')->name('incubatee.task.store');
    Route::post('/incubatee/todo/update/{id}', 'IncubationController@todoUpdate')->name('incubatee.task.update');
    Route::get('/incubatee/todo/status', 'IncubationController@todoStatus')->name('incubatee.task.status');


    Route::get('/startup/view/chart/{startUpId}', 'IncubationController@startupViewChart')->name('startup.chart');
    Route::get('/incubatee/view', 'IncubationController@incubateeView')->name('incubatee.view');
    Route::get('/startup/view/{startUpId}', 'IncubationController@startupView')->name('startup.view');
    Route::get('/startup/view/{startUpId}/startup-manage-profile', 'IncubationController@startupManageProfile')->name('startup.mngprof');
    Route::post('/update-manage-profile/{startUpId}', 'IncubationController@startupUpdateProfile')->name('startup.updprof');

    Route::get('/startup/private-profile/{startUpId}', 'IncubationController@privateProfile')->name('startup.pvtprof');

    Route::get('/add_customer/{startUpId}', 'IncubationController@addCustomer')->name('startup.addcust');
    Route::post('/add-customer-action/{startUpId}', 'IncubationController@addCustomerAction')->name('startup.addcustact');
    Route::get('/customer/edit/{id}', 'IncubationController@custEdit')->name('startup.customer.edit');
    Route::post('/customer/update/{id}', 'IncubationController@custUpdate')->name('startup.customer.update'); 
    Route::get('/customer/destroy/{id}', 'IncubationController@custDestroy')->name('startup.customer.destroy');


    Route::get('/add_financials/{startUpId}', 'IncubationController@addFinancial')->name('startup.addfin');
    Route::post('/add-financials-action/{startUpId}', 'IncubationController@addFinancialAction')->name('startup.addfinact');
    Route::get('/financials/edit/{id}', 'IncubationController@financialEdit')->name('startup.finyear.edit');
    Route::post('/financials/update/{id}', 'IncubationController@financialUpdate')->name('startup.finyear.update'); 
    Route::get('/financials/destroy/{id}', 'IncubationController@financialDestroy')->name('startup.finyear.destroy');

    Route::get('/add_financials_month/{startUpId}', 'IncubationController@addFinancialMonth')->name('startup.addfinmon');
    Route::post('/add-financials-month-action/{startUpId}', 'IncubationController@addFinancialMonthAction')->name('startup.addfinmonact');
    Route::get('/financialsmonth/edit/{id}', 'IncubationController@financialMonthEdit')->name('startup.finmonth.edit');
    Route::post('/financialsmonth/update/{id}', 'IncubationController@financialMonthUpdate')->name('startup.finmonth.update'); 
    Route::get('/financialsmonth/destroy/{id}', 'IncubationController@financialMonthDestroy')->name('startup.finmonth.destroy');

    Route::get('/add_financials_expenses/{startUpId}', 'IncubationController@addFinancialExpenses')->name('startup.addfinexp');
	Route::post('/add-financials-expenses-action/{startUpId}', 'IncubationController@addFinancialExpenseAction')->name('startup.addfinexpact');
	Route::get('/financialsexpenses/edit/{id}', 'IncubationController@financialExpenseEdit')->name('startup.finexp.edit');
	Route::post('/financialsexpenses/update/{id}', 'IncubationController@financialExpenseUpdate')->name('startup.finexp.update'); 
	Route::get('/financialsexpenses/destroy/{id}', 'IncubationController@financialExpenseDestroy')->name('startup.finexp.destroy');



	Route::get('/add_order_pipeline/{startUpId}', 'IncubationController@addOrderPipeline')->name('startup.addorderpipe');
	Route::post('/add-monthly-order-pipeline/{startUpId}', 'IncubationController@addOrderPipelineAction')->name('startup.addorderpipeact');
	Route::get('/orderpipeline/edit/{id}', 'IncubationController@OrderPipelineEdit')->name('startup.orderpipe.edit');
	Route::post('/orderpipeline/update/{id}', 'IncubationController@OrderPipelineUpdate')->name('startup.orderpipe.update'); 
	Route::get('/orderpipeline/destroy/{id}', 'IncubationController@OrderPipelineDestroy')->name('startup.orderpipe.destroy');



	Route::get('/add_yearly_targets/{startUpId}', 'IncubationController@addYearlyTarget')->name('startup.addyearlytarget');
	Route::post('/add-yearly-targets-action/{startUpId}', 'IncubationController@addYearlyTargetAction')->name('startup.addyearlytargetact');
	Route::get('/yearlytargets/edit/{id}', 'IncubationController@yearlyTargetEdit')->name('startup.targets.edit');
	Route::post('/yearlytargets/update/{id}', 'IncubationController@yearlyTargetUpdate')->name('startup.targets.update'); 
	Route::get('/yearlytargets/destroy/{id}', 'IncubationController@yearlyTargetDestroy')->name('startup.targets.destroy');




	Route::get('/add_impacts/{startUpId}', 'IncubationController@addImpacts')->name('startup.addimpact');
	Route::post('/add-impacts-action/{startUpId}', 'IncubationController@addImpactsAction')->name('startup.addimpactact');
	Route::get('/impacts/edit/{id}', 'IncubationController@impactsEdit')->name('startup.impacts.edit');
	Route::post('/impacts/update/{id}', 'IncubationController@impactsUpdate')->name('startup.impacts.update'); 
	Route::get('/impacts/destroy/{id}', 'IncubationController@impactsDestroy')->name('startup.impacts.destroy');




	Route::get('/add_funding_needs/{startUpId}', 'IncubationController@addFundingNeeds')->name('startup.addfunding');
	Route::post('/add-funding-needs-action/{startUpId}', 'IncubationController@addFundingNeedsAction')->name('startup.addfundingact');
	Route::get('/fundingneeds/edit/{id}', 'IncubationController@fundingNeedsEdit')->name('startup.needs.edit');
	Route::post('/fundingneeds/update/{id}', 'IncubationController@fundingNeedsUpdate')->name('startup.needs.update'); 
	Route::get('/fundingneeds/destroy/{id}', 'IncubationController@fundingNeedsDestroy')->name('startup.needs.destroy');




	Route::get('/add_compliance_checks/{startUpId}', 'IncubationController@addComplianceCheck')->name('startup.addcompl');
	Route::post('/add-compliance-checks-action/{startUpId}', 'IncubationController@addComplianceCheckAction')->name('startup.addcomplact');
	Route::get('/compliancechecks/edit/{id}', 'IncubationController@complianceChecksEdit')->name('startup.compliancechecks.edit');
	Route::post('/compliancechecks/update/{id}', 'IncubationController@complianceChecksUpdate')->name('startup.compliancechecks.update'); 
	Route::get('/compliancechecks/destroy/{id}', 'IncubationController@complianceChecksDestroy')->name('startup.compliancechecks.destroy');



    Route::get('/incubatee/diagnostics/{startUpId}', 'IncubationController@diagnosticsList')->name('diagnosticsList');
    Route::match(['get', 'post'], '/incubatee/add-diagnostics/{startUpId}', 'IncubationController@addDiagnostic')->name('addDiagnostic');


    Route::match(['get', 'post'], '/incubatee/edit-diagnostics/{startUpId}/{diagnosticId}', 'IncubationController@editDiagnostic')->name('editDiagnostic');

    Route::get('/incubatee/parameter-list', 'IncubationController@viewParameterList')->name('viewParameterList');
    Route::get('/incubatee/question-answer-list', 'IncubationController@viewQuestAnsList')->name('viewQuestAnsList');
  });
}); //end incubation prefix