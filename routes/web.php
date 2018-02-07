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
//afiliates
Route::group(['middleware' => 'auth'], function() {
	
	Route::get('/', 'HomeController@index')->name("main");

	Route::resource('affiliate', 'AffiliateController');
	Route::patch('/update_affiliate/{affiliate}','AffiliateController@update')->name('update_affiliate');
	Route::patch('/update_affiliate_police/{affiliate}','AffiliateController@update_affiliate_police')->name('update_affiliate_police');
	Route::get('get_all_affiliates', 'AffiliateController@getAllAffiliates');
	


	Route::get('changerol','UserController@changerol');
	Route::post('postchangerol','UserController@postchangerol');

	//retirement fund
	//RetirementFundRequirements
	//Route::resource('ret_fun', 'RetirementFundRequirementController@retFun');
	Route::get('affiliate/{affiliate}/ret_fun', 'RetirementFundRequirementController@retFun');
	// Route::get('/home', 'HomeController@index')->name('home');
	Route::get('get_all_ret_fun', 'RetirementFundController@getAllRetFun');
	Route::resource('ret_fun', 'RetirementFundController');
	Route::get('affiliate/{affiliate}/procedure_create', 'RetirementFundRequirementController@generateProcedure');
	Route::get('affiliate/{affiliate}/ret_fun/create', 'RetirementFundController@generateProcedure');

	//QuotaAidMortuory
	Route::get('affiliate/{affiliate}/quota_aid/create', 'QuotaAidMortuaryController@generateProcedure');
	Route::get('get_all_quota_aid', 'QuotaAidMortuaryController@getAllQuotaAid');
	Route::resource('quota_aid', 'QuotaAidMortuaryController');
	
});

