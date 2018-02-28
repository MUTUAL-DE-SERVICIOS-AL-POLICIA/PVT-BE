<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
 */
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/minor', 'HomeController@minor')->name("minor");

Auth::routes();
// User 
Route::resource('user', 'UserController');
//afiliates
Route::group(['middleware' => 'auth'], function () {
                
	Route::get('/', 'HomeController@index')->name("main");
        
        //ROUTES TO E SYSTEM PARAMENTERS
        Route::get('settings','HomeController@settings');
        Route::resource('ret_fun_procedure','RetFunProcedureController');

	Route::resource('affiliate', 'AffiliateController');

	Route::patch('/update_affiliate/{affiliate}', 'AffiliateController@update')->name('update_affiliate');
	Route::patch('/update_affiliate_police/{affiliate}', 'AffiliateController@update_affiliate_police')->name('update_affiliate_police');

	Route::patch('/update_beneficiaries/{retirement_fund}','RetirementFundController@updateBeneficiaries')->name('update_beneficiaries');

	Route::get('get_all_affiliates', 'AffiliateController@getAllAffiliates');



	Route::get('changerol', 'UserController@changerol');
	Route::post('postchangerol', 'UserController@postchangerol');

	//retirement fund
	//RetirementFundRequirements
	//Route::resource('ret_fun', 'RetirementFundRequirementController@retFun');
	Route::get('affiliate/{affiliate}/ret_fun', 'RetirementFundRequirementController@retFun');
	// Route::get('/home', 'HomeController@index')->name('home');
	Route::get('get_all_ret_fun', 'RetirementFundController@getAllRetFun');
	Route::resource('ret_fun', 'RetirementFundController');
	Route::get('affiliate/{affiliate}/procedure_create', 'RetirementFundRequirementController@generateProcedure');

	Route::get('ret_fun/{retirement_fund}/print/reception', 'RetirementFundCertificationController@printReception')->name('ret_fun_print_reception');
	Route::get('affiliate/{affiliate}/print/file', 'RetirementFundCertificationController@printFile')->name('ret_fun_print_file');
	Route::get('ret_fun/{retirement_fund}/print/legal_review', 'RetirementFundCertificationController@printLegalReview')->name('ret_fun_print_legal_review');
	Route::get('ret_fun/{retirement_fund}/print/beneficiaries_qualification', 'RetirementFundCertificationController@printBeneficiariesQualification')->name('ret_fun_print_beneficiaries_qualification');



	Route::get('affiliate/{affiliate}/ret_fun/create', 'RetirementFundController@generateProcedure')->name('create_ret_fun');
	Route::post('ret_fun/{retirement_fund}/legal_review/create', 'RetirementFundController@storeLegalReview')->name('store_ret_fun_legal_review_create');

	Route::patch('/update_information_rf','RetirementFundController@updateInformation')->name('update_information_rf');

	//QuotaAidMortuory
	Route::get('affiliate/{affiliate}/quota_aid/create', 'QuotaAidMortuaryController@generateProcedure')->name('create_quota_aid');
	Route::get('get_all_quota_aid', 'QuotaAidMortuaryController@getAllQuotaAid');
	Route::resource('quota_aid', 'QuotaAidMortuaryController');

	Route::resource('affiliate_folder', 'AffiliateFolderController');

        //searcherController
	Route::get('search/{ci}', 'SearcherController@search');
	Route::get('search_ajax', 'SearcherController@searchAjax');
        
        //Contributions
        Route::resource('contribution','ContributionController');
        Route::get('affiliate/{affiliate}/contribution/edit', 'ContributionController@getAffiliateContributions');                
        Route::post('store_contributions','ContributionController@storeContributions');
        Route::resource('reimbursement','ReimbursementController');       

	Route::resource('contribution', 'ContributionController');
	Route::get('affiliate/{affiliate}/contribution/create', 'ContributionController@generateContribution')->name('create_contribution');
	Route::post('get-interest','ContributionController@getInterest');
		
});

